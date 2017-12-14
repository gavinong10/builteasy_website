<?php

/* helpers/messages.twig */
class __TwigTemplate_313d599b78207a95286a423cde7d8160 extends Twig_Template
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
        echo "
";
        // line 2
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_flash_has_messages", array(), "method")) {
            // line 3
            echo "    <div class=\"span12\">
        ";
            // line 4
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_flash_get_messages", array(), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 5
                echo "            <div class=\"alert ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["message"]) ? $context["message"] : null), "level"), "html", null, true);
                echo "\">
                ";
                // line 6
                echo $this->getAttribute((isset($context["message"]) ? $context["message"] : null), "message");
                echo "
            </div><!-- /.alert -->
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 9
            echo "    </div><!-- /.span12 -->
";
        }
    }

    public function getTemplateName()
    {
        return "helpers/messages.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 9,  36 => 6,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  125 => 17,  121 => 41,  117 => 39,  112 => 37,  108 => 36,  104 => 34,  98 => 32,  96 => 31,  92 => 29,  86 => 26,  83 => 25,  80 => 24,  76 => 23,  74 => 22,  71 => 21,  69 => 20,  65 => 18,  63 => 17,  55 => 16,  52 => 15,  46 => 13,  44 => 12,  41 => 11,  39 => 10,  34 => 7,  26 => 4,  20 => 1,  31 => 5,  28 => 5,);
    }
}
