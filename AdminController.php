<?php
/**
 * @package   ImpressPages
 */

namespace Plugin\ModernModules;


class AdminController
{

    /**
     * WidgetSkeleton.js ask to provide widget management popup HTML. This controller does this.
     * @return \Ip\Response\Json
     * @throws \Ip\Exception\View
     */
    public function widgetPopupHtml()
    {
        $widgetId = ipRequest()->getQuery('widgetId');
        $widgetRecord = \Ip\Internal\Content\Model::getWidgetRecord($widgetId);
        $widgetData = $widgetRecord['data'];

        // Populate form with proper fields
        switch ($widgetRecord['name']) {
            case 'Card':
                //create form prepopulated with current widget data
                $form = $this->cardManagementForm($widgetData);
                break;

            case 'Spacing':
                $form = $this->spacingManagementForm($widgetData);
                break;
            default:
                return new \Ip\Response\Json([
                    'error' => 'Unknown widget',
                    'widget' => $widgetRecord
                ]);
        }


        //Render form and popup HTML
        $viewData = array(
            'form' => $form
        );
        $popupHtml = ipView('view/editPopup.php', $viewData)->render();
        $data = array(
            'popup' => $popupHtml
        );
        //Return rendered widget management popup HTML in JSON format
        return new \Ip\Response\Json($data);
    }


    /**
     * Check widget's posted data and return data to be stored or errors to be displayed
     */
    public function checkCardForm()
    {
        $data = ipRequest()->getPost();
        $form = $this->cardManagementForm();
        $data = $form->filterValues($data); //filter post data to remove any non form specific items
        $errors = $form->validate($data); //http://www.impresspages.org/docs/form-validation-in-php-3
        if ($errors) {
            //error
            $data = array (
                'status' => 'error',
                'errors' => $errors
            );
        } else {
            //success
            unset($data['aa']);
            unset($data['securityToken']);
            unset($data['antispam']);
            $data = array (
                'status' => 'ok',
                'data' => $data

            );
        }
        return new \Ip\Response\Json($data);
    }

    protected function cardManagementForm($widgetData = array())
    {
        $form = new \Ip\Form();

        $form->setEnvironment(\Ip\Form::ENVIRONMENT_ADMIN);

        //setting hidden input field so that this form would be submitted to 'errorCheck' method of this controller. (http://www.impresspages.org/docs/controller)
        $field = new \Ip\Form\Field\Hidden(
            array(
                'name' => 'aa',
                'value' => 'ModernModules.checkCardForm'
            )
        );

        //ADD YOUR OWN FIELDS
        $nameField = new \Ip\Form\Field\Text([
            'name' => 'title',
            'label' => 'Title',
            'value' => !empty($widgetData['title']) ? $widgetData['title'] : null
        ]);

        $descriptionField = new \Ip\Form\Field\RichText([
            'name' => 'text',
            'label' => 'Description',
            'note' => 'Short description of the product.',
            'value' => !empty($widgetData['text']) ? $widgetData['text'] : null
        ]);

        $imgField = new \Ip\Form\Field\RepositoryFile([
            'name' => 'img',
            'label' => 'Thumbnail',
            'value' => !empty($widgetData['img']) ? $widgetData['img'] : null,
            'preview' => 'thumbnails', //or list. This defines how files have to be displayed in the repository browser
            'fileLimit' => 1, //optional. Limit file count that can be selected. -1 For unlimited
            'filterExtensions' => array('jpg', 'jpeg', 'png', 'gif', 'webm', 'ogg') //optional
        ]);

        $urlField = new \Ip\Form\Field\Url([
            'name' => 'link',
            'label' => 'Link',
            'hint' => 'Link to an internal or external page',
            'value' => !empty($widgetData['link']) ? $widgetData['link'] : null,
            'default' => null
        ]);

        // Register fields to form
        $form->addField($field); // Keep at top
        $form->addField($imgField);
        $form->addField($nameField);
        $form->addField($descriptionField);

        $form->addFieldset(new \Ip\Form\Fieldset());
        $form->addField($urlField);
        $form->addField(new \Ip\Form\Field\Text([
            'name' => 'linkLabel',
            'label' => 'Link Label',
            'value' => !empty($widgetData['linkLabel']) ? $widgetData['linkLabel'] : null,
            'default' => null
        ]));

        $form->addField(new \Ip\Form\Field\Checkbox([
            'name' => 'openInTab',
            'label' => 'Open in new tab',
            'value' => !empty($widgetData['openInTab']) ? $widgetData['openInTab'] : false,
            'default' => false
        ]));

        $configFieldset = new \Ip\Form\Fieldset();
        $configFieldset->setLabel('Styling');

        $form->addFieldset($configFieldset);

        $form->addField(new \Ip\Form\Field\Select([
            'name' => 'styling',
            'label' => 'Styling',
            'values' => [
                ['', 'flat'],
                ['raised', 'raised'],
                ['raised material', 'material']
            ],
            'default' => 'flat'
        ]));

        return $form;
    }

    /**
     * Check widget's posted data and return data to be stored or errors to be displayed
     */
    public function checkSpacingForm()
    {
        $data = ipRequest()->getPost();
        $form = $this->spacingManagementForm();
        $data = $form->filterValues($data); //filter post data to remove any non form specific items
        $errors = $form->validate($data); //http://www.impresspages.org/docs/form-validation-in-php-3
        if ($errors) {
            //error
            $data = array (
                'status' => 'error',
                'errors' => $errors
            );
        } else {
            //success
            unset($data['aa']);
            unset($data['securityToken']);
            unset($data['antispam']);
            $data = array (
                'status' => 'ok',
                'data' => $data

            );
        }
        return new \Ip\Response\Json($data);
    }

    protected function spacingManagementForm($widgetData = array())
    {
        $form = new \Ip\Form();

        $form->setEnvironment(\Ip\Form::ENVIRONMENT_ADMIN);

        //setting hidden input field so that this form would be submitted to 'errorCheck' method of this controller. (http://www.impresspages.org/docs/controller)
        $field = new \Ip\Form\Field\Hidden(
            array(
                'name' => 'aa',
                'value' => 'ModernModules.checkSpacingForm'
            )
        );

        $form->addField(new \Ip\Form\Field\Checkbox([
            'name' => 'vertical',
            'label' => 'Vertical Spacing',
            'value' => !empty($widgetData['vertical']) ? $widgetData['vertical'] : false,
            'default' => false
        ]));

        $form->addField(new \Ip\Form\Field\Number([
            'name' => 'distance',
            'label' => 'Distance',
            'value' => !empty($widgetData['distance']) ? $widgetData['distance'] : 30,
            'default' => 30
        ]));

        // Register fields to form
        $form->addField($field); // Keep at top

        return $form;
    }

}
