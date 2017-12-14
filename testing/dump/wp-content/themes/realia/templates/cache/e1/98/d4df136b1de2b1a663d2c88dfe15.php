<?php

/* properties/carousel.twig */
class __TwigTemplate_e198d4df136b1de2b1a663d2c88dfe15 extends Twig_Template
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

";
        // line 3
        if ((isset($context["properties"]) ? $context["properties"] : null)) {
            // line 4
            echo "    <div class=\"carousel\">
        ";
            // line 5
            if ((isset($context["title"]) ? $context["title"] : null)) {
                // line 6
                echo "            ";
                echo (isset($context["before_title"]) ? $context["before_title"] : null);
                echo (isset($context["title"]) ? $context["title"] : null);
                echo (isset($context["after_title"]) ? $context["after_title"] : null);
                echo "
        ";
            }
            // line 8
            echo "
        <div class=\"content\">
            <a class=\"carousel-prev\">";
            // line 10
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Previous", 1 => "aviators"), "method"), "html", null, true);
            echo "</a>
            <a class=\"carousel-next\">";
            // line 11
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Next", 1 => "aviators"), "method"), "html", null, true);
            echo "</a>

            <ul class=\"properties-grid unstyled\">
                ";
            // line 14
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["properties"]) ? $context["properties"] : null));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
                // line 15
                echo "                    <li>
                        ";
                // line 16
                $this->env->loadTemplate("properties/property-box-small.twig")->display($context);
                // line 17
                echo "                    </li>
                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 19
            echo "            </ul>
        </div>
        <!-- /.content -->
    </div><!-- /.carousel -->
";
        }
        // line 24
        echo "

";
        // line 26
        echo (isset($context["after_widget"]) ? $context["after_widget"] : null);
    }

    public function getTemplateName()
    {
        return "properties/carousel.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  101 => 26,  97 => 24,  90 => 19,  75 => 17,  73 => 16,  70 => 15,  53 => 14,  47 => 11,  43 => 10,  39 => 8,  31 => 6,  29 => 5,  26 => 4,  24 => 3,  19 => 1,);
    }
}
