<?php
    class Cible_Paginator
    {
        protected $_db;
        protected $_view;
        protected $_request;

        /**
         * Constructor
         *
         * Registers form view helper as decorator
         *
         * @param mixed $options
         * @return void
         */
        public function __construct($select, $tables, $field_list, $options = null){
            $this->_db = Zend_registry::get('db');
            $_frontController = Zend_Controller_Front::getInstance();
            $this->_request = $_frontController->getRequest();

            if (null === $this->_view) {
                require_once 'Zend/Controller/Action/HelperBroker.php';
                $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
                $this->_view = $viewRenderer->view;
            }

            if( !empty($options['actionKey']) )
                $this->_view->actionKey = $options['actionKey'];

            if( !empty($options['commands']) )
                $this->_view->assign('commands', $options['commands']);

            if( !empty($options['to-excel-action']) )
                $this->_view->assign('to_excel_action', $options['to-excel-action']);
            else
                $this->_view->assign('to_excel_action', 'to-excel');

            if( !empty($options['disable-export-to-excel']) )
                $this->_view->assign('disable_export_to_excel', $options['disable-export-to-excel']);
            else
                $this->_view->assign('disable_export_to_excel', 'false');

            if( !empty($options['enable-print']) )
            {
                $this->_view->assign('enable_print', $options['enable-print']);
                $this->_view->headScript()->appendFile(
                        $this->_view->locateFile('jquery.printElement.min.js'));
            }
            else
                $this->_view->assign('enable_print', 'false');

            if( !empty($options['filters'])){
                $this->_view->assign('filters', $options['filters']);

                foreach($options['filters'] as $key => $filter){
                    $filter_val = $this->_request->getParam($key);
                    if( !empty($filter_val) )
                        if($filter['associatedTo'] <> ''){
                            if(!empty($filter['kindOfFilter']) && $filter['kindOfFilter'] == 'list'){
                                $select->where("{$filter['associatedTo']} = '$filter_val'
                                                OR {$filter['associatedTo']} like '%$filter_val%'
                                                OR {$filter['associatedTo']} like '%,$filter_val'
                                                OR {$filter['associatedTo']} like '$filter_val,%'
                                                OR {$filter['associatedTo']} like '%,$filter_val,%'
                                 ");
                            }
                            else
                                $select->where("{$filter['associatedTo']} = ?", $filter_val);
                        }

                }

            } else {
                $this->_view->assign('filters', array());
            }


            if( !empty($options['action_panel']) ){
                if( !empty($options['action_panel']) )
                    $field_list['action_panel'] = $options['action_panel'];

                if( !empty($options['action_panel']['actions'] ) )
                    $this->_view->assign('action_links', $options['action_panel']['actions']);
            }

            $this->_view->assign('field_list', $field_list);

            if( $this->_request->getParam('order')){

                if( in_array( $this->_request->getParam('order'), array_keys($field_list) ) ){

                    $direction = 'ASC';
                    if( in_array($this->_request->getParam('order-direction'), array('ASC','DESC') ) )
                        $direction = $this->_request->getParam('order-direction');

                    $select->order( "{$this->_request->getParam('order')} {$direction}" );

                    $this->_view->assign('order', $this->_request->getParam('order'));
                    $this->_view->assign('order_direction', $this->_request->getParam('order-direction'));
                }
            }

            $searchfor = $this->_request->getParam('searchfor');

            if( $searchfor ){

                $searching_on = array();
                $search_keywords = explode(' ', $searchfor);

                foreach($tables as $table => $columns){
                    foreach($columns as $column){
                        $doSearch = true;
                        if(isset($options['onlyColumns'])){
                            if(!in_array($column,$options['onlyColumns'])){
                                    $doSearch = false;                                }
                        }
                        else{
                            if(isset($options['excludedColums'])){
                                if(in_array($column,$options['excludedColums'])){
                                    $doSearch = false;
                                }
                            }
                        }
                        if($doSearch==true){
                            array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$searchfor}%"));
                            foreach( $search_keywords as $keyword )
                                array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$keyword}%"));
                       }
                    }
                }

                if( !empty($searching_on) )
                    $select->where(implode(' OR ', $searching_on));
            }
            $this->_view->assign('searchfor', $searchfor );

            $adapter = new Zend_Paginator_Adapter_DbSelect(
                $select
            );

            $paginator = new Zend_Paginator($adapter);
            $_config = Zend_Registry::get('config');

            $itemPerPage = 10;
            if( !empty($_config->lists->itemPerPage) ){
                $itemPerPage = $_config->lists->itemPerPage;
            }

            if( !empty($options['list_options']['perPage']) )
                $itemPerPage = $options['list_options']['perPage'];

            if( $this->_request->getParam('perPage') ){
                $itemPerPage = $this->_request->getParam('perPage') == 'all' ? $paginator->getTotalItemCount() : $this->_request->getParam('perPage');
            }

            $pageRange = 5;
            if( !empty($_config->lists->pageRange) ){
                $pageRange = $_config->lists->pageRange;
            }

            $paginator->setItemCountPerPage($itemPerPage);
            $paginator->setCurrentPageNumber($this->_request->getParam('page'));
            $paginator->setPageRange($pageRange);

            $this->_view->assign('paginator', $paginator);
        }

        public function render(){

            echo $this->_view->partial('partials/generic.list.phtml', array(
                'paginator'       => $this->_view->paginator,
                'searchfor'       => $this->_view->searchfor,
                'order'           => $this->_view->order,
                'order_direction' => $this->_view->order_direction,
                'field_list'      => $this->_view->field_list,
                'action_links'    => $this->_view->action_links,
                'filters'         => $this->_view->filters,
                'disable_export_to_excel' => $this->_view->disable_export_to_excel,
                'enable_print'    => $this->_view->enable_print,
                'to_excel_action' => $this->_view->to_excel_action,
                'actionKey'       => $this->_view->actionKey,
                'commands'        => $this->_view->commands

            ));

        }
    }
