<?php

/* layout-homepage.twig */
class __TwigTemplate_d4b1b3b967143112039f02671cb0ceea extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_header", array(), "method"), "html", null, true);
        echo "

<div id=\"content\" class=\"clearfix\">
    ";
        // line 4
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "top"), "method")) {
            // line 5
            echo "        ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "top"), "method"), "html", null, true);
            echo "
    ";
        }
        // line 7
        echo "
    <div class=\"container\">
        <div class=\"row\">
            ";
        // line 10
        $this->env->loadTemplate("helpers/messages.twig")->display($context);
        // line 11
        echo "
            ";
        // line 12
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "content-top"), "method")) {
            // line 13
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "content-top"), "method"), "html", null, true);
            echo "
            ";
        }
        // line 15
        echo "
            <div id=\"main\" class=\"";
        // line 16
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "sidebar-primary"), "method") && ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "homepage", 2 => "hide_sidebar"), "method") != "on"))) {
            echo "span9";
        } else {
            echo "span12";
        }
        echo "\">

                ";
        // line 18
        $this->displayBlock('content', $context, $blocks);
        // line 19
        echo "            </div><!-- /#main -->

            ";
        // line 21
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "homepage", 2 => "hide_sidebar"), "method") != "on")) {
            // line 22
            echo "                ";
            if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "sidebar-primary"), "method") || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "property-detail"), "method"))) {
                // line 23
                echo "                    <div class=\"sidebar span3\">
                        ";
                // line 24
                if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_singular", array(0 => "property"), "method")) {
                    // line 25
                    echo "                            ";
                    if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "dynamic_sidebar", array(0 => "property-detail"), "method"))) {
                    }
                    // line 26
                    echo "                        ";
                }
                // line 27
                echo "
                        ";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "sidebar-primary"), "method"), "html", null, true);
                echo "
                    </div><!-- /#sidebar -->
                ";
            }
            // line 31
            echo "            ";
        }
        // line 32
        echo "        </div><!-- /.row -->

        ";
        // line 34
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "content-bottom"), "method")) {
            // line 35
            echo "            ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "content-bottom"), "method"), "html", null, true);
            echo "
        ";
        }
        // line 37
        echo "    </div><!-- /.container -->

    ";
        // line 39
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "bottom"), "method")) {
            // line 40
            echo "        ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "bottom"), "method"), "html", null, true);
            echo "
    ";
        }
        // line 42
        echo "</div><!-- /#content -->

";
        // line 44
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_footer", array(), "method"), "html", null, true);
    }

    // line 18
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout-homepage.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 18,  127 => 44,  123 => 42,  117 => 40,  115 => 39,  111 => 37,  105 => 35,  103 => 34,  99 => 32,  96 => 31,  90 => 28,  87 => 27,  84 => 26,  80 => 25,  78 => 24,  75 => 23,  72 => 22,  70 => 21,  66 => 19,  64 => 18,  55 => 16,  52 => 15,  46 => 13,  44 => 12,  41 => 11,  39 => 10,  26 => 4,  20 => 1,  57 => 14,  54 => 13,  48 => 10,  45 => 9,  43 => 8,  40 => 7,  34 => 7,  31 => 4,  28 => 5,);
    }
}
