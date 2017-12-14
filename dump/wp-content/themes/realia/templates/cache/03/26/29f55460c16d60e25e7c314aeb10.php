<?php

/* helpers/sidebar-bottom.twig */
class __TwigTemplate_032629f55460c16d60e25e7c314aeb10 extends Twig_Template
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
        if ((($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "bottom-1"), "method") || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "bottom-2"), "method")) || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "bottom-3"), "method"))) {
            // line 2
            echo "    <div class=\"bottom-wrapper\">
        <div class=\"bottom container\">
            <div class=\"bottom-inner row\">
                <div class=\"span4\">
                    ";
            // line 6
            if ((!aviators_templates_helpers_dynamic_sidebar("bottom-1"))) {
                // line 7
                echo "                    ";
            }
            // line 8
            echo "                </div>

                <div class=\"span4\">
                    ";
            // line 11
            if ((!aviators_templates_helpers_dynamic_sidebar("bottom-2"))) {
                // line 12
                echo "                    ";
            }
            // line 13
            echo "                </div>

                <div class=\"span4\">
                    ";
            // line 16
            if ((!aviators_templates_helpers_dynamic_sidebar("bottom-3"))) {
                // line 17
                echo "                    ";
            }
            // line 18
            echo "                </div>
            </div><!-- /.bottom-inner -->
        </div><!-- /.bottom -->
    </div><!-- /.bottom-wrapper -->
";
        }
    }

    public function getTemplateName()
    {
        return "helpers/sidebar-bottom.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 18,  49 => 17,  47 => 16,  42 => 13,  39 => 12,  37 => 11,  32 => 8,  29 => 7,  27 => 6,  21 => 2,  19 => 1,);
    }
}
