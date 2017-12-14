<?php

/* partners/widget.twig */
class __TwigTemplate_ba2b048da61390ff0ecaca995087d55c extends Twig_Template
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
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 4
            echo "    ";
            echo (isset($context["before_title"]) ? $context["before_title"] : null);
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
            echo (isset($context["after_title"]) ? $context["after_title"] : null);
            echo "
";
        }
        // line 6
        echo "
";
        // line 7
        if ((isset($context["partners"]) ? $context["partners"] : null)) {
            // line 8
            echo "    <div class=\"partners\">
        <div class=\"row\">
            ";
            // line 10
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["partners"]) ? $context["partners"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["partner"]) {
                // line 11
                echo "                <div class=\"span3\">
                    <div class=\"partner\">
                        <a href=\"";
                // line 13
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_post_meta", array(0 => $this->getAttribute((isset($context["partner"]) ? $context["partner"] : null), "ID"), 1 => "_partner_url", 2 => true), "method"), "html", null, true);
                echo "\">
                            ";
                // line 14
                echo $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_the_post_thumbnail", array(0 => $this->getAttribute((isset($context["partner"]) ? $context["partner"] : null), "ID")), "method");
                echo "
                        </a>
                    </div>
                </div><!-- /.span3 -->
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['partner'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 19
            echo "        </div><!-- /.row -->
    </div><!-- /.partners -->
";
        }
        // line 22
        echo (isset($context["after_widget"]) ? $context["after_widget"] : null);
    }

    public function getTemplateName()
    {
        return "partners/widget.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 22,  66 => 19,  55 => 14,  51 => 13,  47 => 11,  43 => 10,  39 => 8,  37 => 7,  34 => 6,  26 => 4,  24 => 3,  19 => 1,);
    }
}
