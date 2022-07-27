<?php

namespace System\View;

use System\View\Traits\HasExtendsContent;
use System\View\Traits\HasIncludeContent;
use System\View\Traits\HasInputsContent;
use System\View\Traits\HasViewLoader;

class ViewBuilder
{
    use HasViewLoader;
    use HasExtendsContent;
    use HasIncludeContent;
    use HasInputsContent;

    public $content;

    public $vars = [];

    private $isApts = true;

    public function run($filePath)
    {
        $this->content = $this->viewLoader($filePath);
        if ($this->isApts) {
            $this->checkExtendsContent();
            $this->checkIncludesContent();
            $this->checkInputsContent();
        }
        Composer::setViews($this->viewNameArray);
        $this->vars = Composer::getVars();
    }
}
