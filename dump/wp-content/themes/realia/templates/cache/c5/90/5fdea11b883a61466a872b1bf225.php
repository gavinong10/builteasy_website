<?php

/* single-property.twig */
class __TwigTemplate_c5905fdea11b883a61466a872b1bf225 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "    <h1 class=\"page-header\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["post"]) ? $context["post"] : null), "post_title"), "html", null, true);
        echo "</h1>

    ";
        // line 6
        $this->env->loadTemplate("properties/slider.twig")->display($context);
        // line 7
        echo "
    <div class=\"property-detail\">
        ";
        // line 9
        $this->env->loadTemplate("properties/overview.twig")->display($context);
        // line 10
        echo "
        ";
        // line 11
        echo $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "do_shortcode", array(0 => $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "apply_filters", array(0 => "the_content", 1 => $this->getAttribute((isset($context["post"]) ? $context["post"] : null), "post_content")), "method")), "method");
        echo "

        ";
        // line 13
        $this->env->loadTemplate("properties/amenities.twig")->display($context);
        // line 14
        echo "
        ";
        // line 15
        $this->env->loadTemplate("properties/property-map.twig")->display($context);
        // line 16
        echo "    </div>

    ";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "comments_template"), "html", null, true);
        echo "
";
    }

    public function getTemplateName()
    {
        return "single-property.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  64 => 18,  60 => 16,  58 => 15,  55 => 14,  53 => 13,  48 => 11,  45 => 10,  43 => 9,  39 => 7,  37 => 6,  31 => 4,  28 => 3,);
    }
}
