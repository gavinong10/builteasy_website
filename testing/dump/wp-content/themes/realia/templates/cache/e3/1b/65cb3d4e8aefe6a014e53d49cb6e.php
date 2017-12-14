<?php

/* properties/amenities.twig */
class __TwigTemplate_e31b65cb3d4e8aefe6a014e53d49cb6e extends Twig_Template
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
        if ((isset($context["amenities"]) ? $context["amenities"] : null)) {
            // line 2
            echo "    <div class=\"row\">
        <div class=\"span12\">
            <h2>";
            // line 4
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "General amenities", 1 => "aviators"), "method"), "html", null, true);
            echo "</h2>

            <div class=\"row\">
                ";
            // line 7
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["amenities"]) ? $context["amenities"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                // line 8
                echo "                    <ul class=\"span2\">
                        ";
                // line 9
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["column"]) ? $context["column"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["amenity"]) {
                    // line 10
                    echo "                            <li class=\"";
                    if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "has_term", array(0 => $this->getAttribute((isset($context["amenity"]) ? $context["amenity"] : null), "term_id"), 1 => "amenities", 2 => $this->getAttribute((isset($context["post"]) ? $context["post"] : null), "ID")), "method")) {
                        echo "checked";
                    } else {
                        echo "plain";
                    }
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["amenity"]) ? $context["amenity"] : null), "name"), "html", null, true);
                    echo "</li>
                        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['amenity'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 12
                echo "                    </ul><!-- /.span2 -->
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 14
            echo "            </div>
            <!-- /.row -->
        </div>
        <!-- /.span12 -->
    </div><!-- /.row -->
";
        }
    }

    public function getTemplateName()
    {
        return "properties/amenities.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 12,  42 => 10,  38 => 9,  35 => 8,  25 => 4,  217 => 84,  210 => 80,  204 => 77,  200 => 75,  198 => 74,  195 => 73,  188 => 70,  184 => 69,  181 => 68,  179 => 67,  176 => 66,  173 => 65,  167 => 62,  163 => 61,  160 => 60,  157 => 59,  152 => 57,  149 => 56,  143 => 53,  139 => 52,  136 => 51,  133 => 50,  125 => 46,  121 => 45,  117 => 43,  111 => 40,  107 => 39,  104 => 38,  102 => 37,  96 => 34,  92 => 33,  88 => 31,  83 => 28,  80 => 27,  74 => 25,  72 => 24,  66 => 22,  50 => 16,  32 => 9,  24 => 4,  159 => 37,  155 => 58,  151 => 34,  137 => 33,  131 => 49,  126 => 29,  120 => 26,  115 => 25,  112 => 24,  95 => 23,  87 => 17,  73 => 16,  67 => 13,  54 => 9,  47 => 8,  44 => 7,  27 => 6,  21 => 2,  19 => 1,  64 => 14,  60 => 20,  58 => 19,  55 => 14,  53 => 17,  48 => 15,  45 => 10,  43 => 9,  39 => 7,  37 => 11,  31 => 7,  28 => 3,);
    }
}
