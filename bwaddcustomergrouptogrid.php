<?php


if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . 'bwaddcustomergrouptogrid' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Search\Filters\CustomerFilters;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Bwaddcustomergrouptogrid extends Module
{
    protected $config_form = false;
    private $groupChoices;

    public function __construct()
    {
        $this->name = 'bwaddcustomergrouptogrid';
        $this->version = '1.0.0';

        $this->author = 'Bwlab'; //TODO edit
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Add Customer Group to Customer Grid', [], 'Modules.Bwaddcustomergrouptogrid.Admin');
        $this->description = $this->trans('Add Customer Group to Customer Grid', [], 'Modules.Bwaddcustomergrouptogrid.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => '1.7.8.9');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionCustomerGridQueryBuilderModifier')
            && $this->registerHook('actionCustomerGridDefinitionModifier');
    }

    public function uninstall()
    {

        return parent::uninstall();
    }

    public function hookActionCustomerGridQueryBuilderModifier($params)
    {
        if (true === (bool)(int)Configuration::get('PS_GROUP_FEATURE_ACTIVE')) {
            /** @var CustomerFilters $search_criteria */
            $search_criteria = $params['search_criteria'];
            $filters = $search_criteria->getFilters();

            $searchQueryBuilder = $params['search_query_builder'];

            $searchQueryBuilder->addSelect('_gl.name as default_group')
                ->leftJoin('c', _DB_PREFIX_ . 'group_lang', '_gl', 'c.id_default_group = _gl.id_group')
                ->where('_gl.id_lang = ' . (int)$this->context->language->id);
            if (isset($filters['default_group']) && !empty($filters['default_group'])) {
                $search_id_default_group = (int)$filters['default_group'];
                $searchQueryBuilder->andWhere('_gl.id_group = ' . (int)$search_id_default_group);
            }
        }
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookActionCustomerGridDefinitionModifier(array $params)
    {

        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];


        /** @var ColumnCollection $columns */
        $columns = $definition->getColumns();
        if (true === (bool)(int)Configuration::get('PS_GROUP_FEATURE_ACTIVE')) {
            $columns->addAfter(
                'email',
                (new DataColumn('default_group'))
                    ->setName($this->trans('Group', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'default_group',
                    ])
            );

            $filters = $definition->getFilters();
            $service_groups = $this->get('prestashop.adapter.form.choice_provider.group_by_id_choice_provider');
            $filters->add(
                (new Filter('default_group', ChoiceType::class))
                    ->setTypeOptions([
                        'choices' => $service_groups->getChoices(),
                        'expanded' => false,
                        'multiple' => false,
                        'required' => false,
                        'choice_translation_domain' => false,
                    ])
                    ->setAssociatedColumn('default_group')
            );
        }


    }
}
