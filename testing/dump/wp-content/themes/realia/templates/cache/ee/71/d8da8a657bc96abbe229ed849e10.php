<?php

/* properties/slider-large.twig */
class __TwigTemplate_ee71d8da8a657bc96abbe229ed849e10 extends Twig_Template
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
        if ((isset($context["properties"]) ? $context["properties"] : null)) {
            // line 2
            echo "    <div class=\"container\">
        <div class=\"slider-wrapper\">
            <div class=\"slider\">
                <div class=\"slider-inner\">
                    <div class=\"row\">
                        <div class=\"images span9\">
                            <div class=\"iosSlider\">
                                <div class=\"slider-content\">
                                    ";
            // line 10
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["properties"]) ? $context["properties"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
                // line 11
                echo "                                        <div class=\"slide\">
                                            <img src=\"";
                // line 12
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "slider_image"), "html", null, true);
                echo "\" alt=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "post_title"), "html", null, true);
                echo "\">

                                            <div class=\"slider-info\">
                                                <div class=\"price\" style=\"width: 100%;\">
                                                    <h2 style=\"width: 100%;\"> ";
                // line 16
                if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_custom_text"), 0)) {
                    // line 17
                    echo "                                                            ";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_custom_text"), 0), "html", null, true);
                    echo "
                                                        ";
                } else {
                    // line 19
                    echo "                                                            ";
                    echo $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_price_format", array(0 => $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_price"), 0)), "method");
                    echo "

                                                            ";
                    // line 21
                    if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_price_suffix"), 0)) {
                        // line 22
                        echo "                                                                ";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_price_suffix"), 0), "html", null, true);
                        echo "
                                                            ";
                    }
                    // line 24
                    echo "                                                        ";
                }
                // line 25
                echo "                                                    </h2>
                                                </div>
                                                <!-- /.price -->
                                                <h2>
                                                    <a href=\"";
                // line 29
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_permalink", array(0 => $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "ID")), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "post_title"), "html", null, true);
                echo "</a>
                                                </h2>

                                                <div class=\"bathrooms\">";
                // line 32
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_bathrooms"), 0), "html", null, true);
                echo "</div>
                                                <!-- /.bathrooms -->
                                                <div class=\"bedrooms\">";
                // line 34
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_bedrooms"), 0), "html", null, true);
                echo "</div>
                                                <!-- /.bedrooms -->

                                            </div>
                                            <!-- /.slider-info -->
                                        </div><!-- /.slide -->
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 41
            echo "                                </div>
                                <!-- /.slider-content -->
                            </div>
                            <!-- .iosSlider -->

                            <ul class=\"navigation\">
                                ";
            // line 47
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
                // line 48
                echo "                                    <li ";
                if ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "first")) {
                    echo "class=\"active\"";
                }
                echo "><a>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "index"), "html", null, true);
                echo "</a></li>
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
            // line 50
            echo "                            </ul>
                            <!-- /.navigation-->
                        </div>
                        <!-- /.images -->

                        <div class=\"span3\">
                            ";
            // line 56
            $this->env->loadTemplate("properties/filter.twig")->display($context);
            // line 57
            echo "                        </div>
                        <!-- /.span3 -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.slider-inner -->
            </div>
            <!-- /.slider -->
        </div>
        <!-- /.slider-wrapper -->
    </div><!-- /.container -->
";
        }
    }

    public function getTemplateName()
    {
        return "properties/slider-large.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  160 => 57,  158 => 56,  150 => 50,  129 => 48,  112 => 47,  104 => 41,  91 => 34,  86 => 32,  78 => 29,  72 => 25,  69 => 24,  63 => 22,  61 => 21,  55 => 19,  49 => 17,  47 => 16,  38 => 12,  35 => 11,  31 => 10,  21 => 2,  19 => 1,);
    }
}
