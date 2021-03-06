<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\HelperModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use ZLanguage;
use RK\HelperModule\Entity\Factory\HelperFactory;
use RK\HelperModule\Helper\FeatureActivationHelper;
use RK\HelperModule\Helper\ListEntriesHelper;

/**
 * Linker editing form type base class.
 */
abstract class AbstractLinkerType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var HelperFactory
     */
    protected $entityFactory;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * LinkerType constructor.
     *
     * @param TranslatorInterface $translator    Translator service instance
     * @param HelperFactory        $entityFactory Entity factory service instance
     * @param ListEntriesHelper   $listHelper    ListEntriesHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(TranslatorInterface $translator, HelperFactory $entityFactory, ListEntriesHelper $listHelper, FeatureActivationHelper $featureActivationHelper)
    {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->listHelper = $listHelper;
        $this->featureActivationHelper = $featureActivationHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEntityFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addReturnControlField($builder, $options);
        $this->addSubmitButtons($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['linkerImage'] as $uploadFieldName) {
                $entity[$uploadFieldName] = [
                    $uploadFieldName => $entity[$uploadFieldName] instanceof File ? $entity[$uploadFieldName]->getPathname() : null
                ];
            }
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['linkerImage'] as $uploadFieldName) {
                if (is_array($entity[$uploadFieldName])) {
                    $entity[$uploadFieldName] = $entity[$uploadFieldName][$uploadFieldName];
                }
            }
        });
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('linkerImage', 'RK\HelperModule\Form\Type\Field\UploadType', [
            'label' => $this->__('Linker image') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the linker image of the linker')
            ],'required' => true && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => 'gif, jpeg, jpg, png',
            'allowed_size' => 0
        ]);
        
        $builder->add('linkerHeadline', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Linker headline') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the linker headline of the linker')
            ],'required' => true,
        ]);
        
        $builder->add('linkerText', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Linker text') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => '',
                'title' => $this->__('Enter the linker text of the linker')
            ],'required' => true
        ]);
        
        $builder->add('theLink', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('The link') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('You must be carefull with the link settings. It is not validated!')
            ],
            'help' => $this->__('You must be carefull with the link settings. It is not validated!'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the the link of the linker')
            ],'required' => false,
        ]);
        
        $builder->add('boostrapSetting', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Boostrap setting') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('see the definitions at the bootstrap documentation')
            ],
            'help' => $this->__('see the definitions at the bootstrap documentation'),
            'empty_data' => 'col-xs-12 col-sm-6 col-md-3',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the boostrap setting of the linker')
            ],'required' => true,
        ]);
        
        $builder->add('linkerLocale', 'Zikula\Bundle\FormExtensionBundle\Form\Type\LocaleType', [
            'label' => $this->__('Linker locale') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => ' validate-nospace',
                'title' => $this->__('Choose the linker locale of the linker')
            ],'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => array_flip(ZLanguage::getInstalledLanguageNames()),
            'choices_as_values' => true
        ]);
        
        $builder->add('sorting', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
            'label' => $this->__('Sorting') . ':',
            'empty_data' => '0',
            'attr' => [
                'maxlength' => 11,
                'class' => ' validate-digits',
                'title' => $this->__('Enter the sorting of the linker.') . ' ' . $this->__('Only digits are allowed.')
            ],'required' => true,
            'scale' => 0
        ]);
        
        $builder->add('linkerGroup', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Linker group') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('a field to be used for block filtering. We may want to filter the same string here. Please do not use spaces an scpecial characters.')
            ],
            'help' => $this->__('a field to be used for block filtering. We may want to filter the same string here. Please do not use spaces an scpecial characters.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => ' validate-nospace',
                'title' => $this->__('Enter the linker group of the linker')
            ],'required' => false,
        ]);
    }

    /**
     * Adds special fields for moderators.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options)
    {
        if (!$options['hasModeratePermission']) {
            return;
        }
    
        $builder->add('moderationSpecificCreator', 'RK\HelperModule\Form\Type\Field\UserType', [
            'mapped' => false,
            'label' => $this->__('Creator') . ':',
            'attr' => [
                'maxlength' => 11,
                'class' => ' validate-digits',
                'title' => $this->__('Here you can choose a user which will be set as creator')
            ],
            'empty_data' => 0,
            'required' => false,
            'help' => $this->__('Here you can choose a user which will be set as creator')
        ]);
        $builder->add('moderationSpecificCreationDate', 'RK\HelperModule\Form\Type\Field\DateTimeType', [
            'mapped' => false,
            'label' => $this->__('Creation date') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('Here you can choose a custom creation date')
            ],
            'empty_data' => '',
            'required' => false,
            'widget' => 'single_text',
            'help' => $this->__('Here you can choose a custom creation date')
        ]);
    }

    /**
     * Adds the return control field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addReturnControlField(FormBuilderInterface $builder, array $options)
    {
        if ($options['mode'] != 'create') {
            return;
        }
        $builder->add('repeatCreation', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'mapped' => false,
            'label' => $this->__('Create another item after save'),
            'required' => false
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__(/** @Ignore */$action['title']),
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass'],
                    'title' => $this->__(/** @Ignore */$action['description'])
                ]
            ]);
        }
        $builder->add('reset', 'Symfony\Component\Form\Extension\Core\Type\ResetType', [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rkhelpermodule_linker';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'RK\HelperModule\Entity\LinkerEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createLinker();
                },
                'error_mapping' => [
                    'linkerImage' => 'linkerImage.linkerImage',
                ],
                'mode' => 'create',
                'actions' => [],
                'hasModeratePermission' => false,
            ])
            ->setRequired(['entity', 'mode', 'actions'])
            ->setAllowedTypes([
                'mode' => 'string',
                'actions' => 'array',
                'hasModeratePermission' => 'bool',
            ])
            ->setAllowedValues([
                'mode' => ['create', 'edit']
            ])
        ;
    }
}
