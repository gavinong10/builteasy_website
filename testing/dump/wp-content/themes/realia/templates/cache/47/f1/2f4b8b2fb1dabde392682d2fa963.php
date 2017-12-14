<?php

/* helpers/content-loop.twig */
class __TwigTemplate_47f12f4b8b2fb1dabde392682d2fa963 extends Twig_Template
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
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "have_posts", array(), "method")) {
            // line 2
            echo "
    ";
            // line 3
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["posts"]) ? $context["posts"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
                // line 4
                echo "        ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp_query"]) ? $context["wp_query"] : null), "the_post", array(), "method"), "html", null, true);
                echo "
        ";
                // line 5
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_part", array(0 => "content", 1 => $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_post_format", array(), "method")), "method"), "html", null, true);
                echo "
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 7
            echo "
    ";
            // line 8
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_paging_nav", array(0 => "", 1 => 2, 2 => (isset($context["wp_query"]) ? $context["wp_query"] : null)), "method"), "html", null, true);
            echo "
";
        } else {
            // line 10
            echo "    ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_part", array(0 => "content", 1 => "none"), "method"), "html", null, true);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "helpers/content-loop.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 10,  33 => 5,  21 => 2,  45 => 9,  36 => 6,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  125 => 17,  121 => 41,  117 => 39,  112 => 37,  108 => 36,  104 => 34,  98 => 32,  96 => 31,  92 => 29,  86 => 26,  83 => 25,  80 => 24,  76 => 23,  74 => 22,  71 => 21,  69 => 20,  65 => 18,  63 => 17,  55 => 16,  52 => 15,  46 => 13,  44 => 8,  41 => 7,  39 => 10,  34 => 7,  26 => 4,  20 => 1,  31 => 5,  28 => 4,);
    }
}
