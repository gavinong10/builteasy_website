<?php

/* page-properties-grid.twig */
class __TwigTemplate_2ed9c6ef8730751fb48bdb3b1df337fa extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout-homepage.twig");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout-homepage.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        if ((!twig_test_empty($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "post_title")))) {
            // line 5
            echo "        <h1 class=\"page-header\">";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "post_title");
            echo "</h1>
    ";
        }
        // line 7
        echo "
    ";
        // line 8
        if ((!twig_test_empty((isset($context["content"]) ? $context["content"] : null)))) {
            // line 9
            echo "        <div class=\"page-content properties-grid-content\">
            ";
            // line 10
            echo (isset($context["content"]) ? $context["content"] : null);
            echo "
        </div>
    ";
        }
        // line 13
        echo "
    ";
        // line 14
        $this->env->loadTemplate("properties/properties-grid.twig")->display($context);
    }

    public function getTemplateName()
    {
        return "page-properties-grid.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 14,  54 => 13,  48 => 10,  45 => 9,  43 => 8,  40 => 7,  34 => 5,  31 => 4,  28 => 3,);
    }
}
