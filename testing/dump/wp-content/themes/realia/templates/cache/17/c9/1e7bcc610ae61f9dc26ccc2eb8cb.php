<?php

/* helpers/sidebar-content-bottom.twig */
class __TwigTemplate_17c91e7bcc610ae61f9dc26ccc2eb8cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"content-bottom-wrapper\">
\t<div class=\"content-bottom\">
\t\t<div class=\"content-bottom-inner\">
\t\t\t";
        // line 4
        if ((!aviators_templates_helpers_dynamic_sidebar("content-bottom"))) {
        }
        // line 5
        echo "\t\t</div><!-- /.content-bottom-inner -->
\t</div><!-- /.content-bottom -->
</div><!-- /.content-bottom-wrapper -->";
    }

    public function getTemplateName()
    {
        return "helpers/sidebar-content-bottom.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 5,  24 => 4,  19 => 1,);
    }
}
