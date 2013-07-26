<?php

class Module_Index extends Core_Controller {

    public function indexPage() {
        $this->setTemplateType('raintpl');
        $a = $this->getParam('a', 2);
        $this->a = $a;
        $this->testVariable = 3 + 4;
    }
    public function secondPage(){
        $this->redirect("/module/index/index");
    }
    public function thirdPage(){
        $this->forward("/module/index/index");
    }
}

?>
