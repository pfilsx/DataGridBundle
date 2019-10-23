<?php


namespace Pfilsx\DataGrid\Maker;


use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

class MakeGrid extends AbstractMaker
{

    private $entityHelper;
    private $gridTypeRenderer;

    public function __construct(DoctrineHelper $entityHelper, GridTypeRenderer $gridTypeRenderer)
    {
        $this->entityHelper = $entityHelper;
        $this->gridTypeRenderer = $gridTypeRenderer;
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     *
     * @return string
     */
    public static function getCommandName(): string
    {
        return 'make:grid';
    }

    /**
     * Configure the command: set description, input arguments, options, etc.
     *
     * By default, all arguments will be asked interactively. If you want
     * to avoid that, use the $inputConfig->setArgumentAsNonInteractive() method.
     *
     * @param Command $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new grid class')
            ->addArgument('name', InputArgument::OPTIONAL, sprintf('The name of the grid class (e.g. <fg=yellow>%sGridType</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('bound-class', InputArgument::OPTIONAL, 'The name of Entity or fully qualified model class name that the new form will be bound to (empty for none)');
//            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeForm.txt'));
        $inputConfig->setArgumentAsNonInteractive('bound-class');
    }

    /**
     * Configure any library dependencies that your maker requires.
     *
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            DoctrineBundle::class,
            'orm'
        );
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('bound-class')) {
            $argument = $command->getDefinition()->getArgument('bound-class');
            $entities = $this->entityHelper->getEntitiesForAutocomplete();
            $question = new Question($argument->getDescription());
            $question->setValidator(function ($answer) use ($entities) {return Validator::existsOrNull($answer, $entities); });
            $question->setAutocompleterValues($entities);
            $question->setMaxAttempts(3);
            $input->setArgument('bound-class', $io->askQuestion($question));
        }
    }

    /**
     * Called after normal code generation: allows you to do anything.
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Generator $generator
     * @throws ReflectionException
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $gridClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Grid\\',
            'Type'
        );

        $gridFieldsWithTypes = ['field_name' => null];

        $boundClass = $input->getArgument('bound-class');
        $boundClassDetails = null;
        if (null !== $boundClass) {
            $gridFieldsWithTypes = [];
            $boundClassDetails = $generator->createClassNameDetails(
                $boundClass,
                'Entity\\'
            );
            $doctrineMetadata = $this->entityHelper->getMetadata($boundClassDetails->getFullName());
            if ($doctrineMetadata instanceof ClassMetadata) {
                foreach ($doctrineMetadata->getFieldNames() as $fieldName){
                    $gridFieldsWithTypes[$fieldName] = $doctrineMetadata->getTypeOfField($fieldName);
                }
                foreach ($doctrineMetadata->associationMappings as $fieldName => $relation) {
                    if ($relation['type'] === ClassMetadata::MANY_TO_ONE) {
                        $gridFieldsWithTypes[$fieldName] = 'relation';
                    }
                }
            } else {
                $reflect = new ReflectionClass($boundClassDetails->getFullName());
                foreach ($reflect->getProperties() as $prop) {
                    $gridFieldsWithTypes[$prop->getName()] = null;
                }
            }
        }

        $this->gridTypeRenderer->render(
            $gridClassNameDetails,
            $gridFieldsWithTypes,
            $boundClassDetails
        );
        $generator->writeChanges();
        $this->writeSuccessMessage($io);
        $io->text([
            'Next: Add fields to your grid and start using it.',
            'Find the documentation at <fg=yellow>https://github.com/pfilsx/DataGridBundle/blob/master/src/Resources/doc</>',
        ]);
    }
}