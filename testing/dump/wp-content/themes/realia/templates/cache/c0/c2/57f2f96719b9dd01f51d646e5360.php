<?php

/* call-to-action/widget.twig */
class __TwigTemplate_c0c257f2f96719b9dd01f51d646e5360 extends Twig_Template
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
        echo (isset($context["before_widget"]) ? $context["before_widget"] : null);
        echo "

<a class=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["class"]) ? $context["class"] : null), "html", null, true);
        echo " decoration\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["link"]) ? $context["link"] : null), "html", null, true);
        echo "\"></a>

";
        // line 5
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 6
            echo "    ";
            echo (isset($context["before_title"]) ? $context["before_title"] : null);
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
            echo (isset($context["after_title"]) ? $context["after_title"] : null);
            echo "
";
        }
        // line 8
        echo "
";
        // line 9
        if ((isset($context["text"]) ? $context["text"] : null)) {
            // line 10
            echo "    <p>";
            echo twig_escape_filter($this->env, (isset($context["text"]) ? $context["text"] : null), "html", null, true);
            echo "</p>
";
        }
        // line 12
        echo "
";
        // line 13
        if ((isset($context["link"]) ? $context["link"] : null)) {
            // line 14
            echo "    <a href=\"";
            echo twig_escape_filter($this->env, (isset($context["link"]) ? $context["link"] : null), "html", null, true);
            echo "\" class=\"btn btn-primary\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Read more", 1 => "aviators"), "method"), "html", null, true);
            echo "</a>
";
        }
        // line 16
        echo "
";
        // line 17
        echo (isset($context["after_widget"]) ? $context["after_widget"] : null);
    }

    public function getTemplateName()
    {
        return "call-to-action/widget.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 17,  65 => 16,  57 => 14,  55 => 13,  52 => 12,  46 => 10,  44 => 9,  41 => 8,  33 => 6,  31 => 5,  24 => 3,  19 => 1,);
    }
}
