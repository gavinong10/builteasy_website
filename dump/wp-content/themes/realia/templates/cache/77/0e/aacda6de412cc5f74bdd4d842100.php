<?php

/* properties/map.twig */
class __TwigTemplate_770eaacda6de412cc5f74bdd4d842100 extends Twig_Template
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
        echo "<div class=\"map-wrapper\">
    ";
        // line 2
        if ((isset($context["show_filter"]) ? $context["show_filter"] : null)) {
            // line 3
            echo "        ";
            if ((!(isset($context["horizontal_filter"]) ? $context["horizontal_filter"] : null))) {
                // line 4
                echo "            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"span3\">
                        ";
                // line 7
                $this->env->loadTemplate("properties/filter.twig")->display($context);
                // line 8
                echo "                    </div>
                    <!-- /.span3 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container -->
        ";
            }
            // line 14
            echo "    ";
        }
        // line 15
        echo "
    <div class=\"map\">

        <script type=\"text/javascript\">
            jQuery(document).ready(function (\$) {
                var map = \$('#map').aviators_map({
                    locations              : ";
        // line 21
        $this->env->loadTemplate("properties/helpers/locations.twig")->display($context);
        echo ",
                    types                  : ";
        // line 22
        $this->env->loadTemplate("properties/helpers/types.twig")->display($context);
        echo ",
                    contents               : ";
        // line 23
        $this->env->loadTemplate("properties/helpers/contents.twig")->display($context);
        echo ",
                    transparentMarkerImage : '";
        // line 24
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/img/marker-transparent.png',
                    transparentClusterImage: '";
        // line 25
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/img/markers/cluster-transparent.png',
                    zoom                   : ";
        // line 26
        echo twig_escape_filter($this->env, (isset($context["zoom"]) ? $context["zoom"] : null), "html", null, true);
        echo ",
                    center                 : {
                        latitude : ";
        // line 28
        echo twig_escape_filter($this->env, (isset($context["latitude"]) ? $context["latitude"] : null), "html", null, true);
        echo ",
                        longitude: ";
        // line 29
        echo twig_escape_filter($this->env, (isset($context["longitude"]) ? $context["longitude"] : null), "html", null, true);
        echo "
                    },
                    filterForm             : '.map-filtering',
                    enableGeolocation      : '";
        // line 32
        echo twig_escape_filter($this->env, (isset($context["enable_geolocation"]) ? $context["enable_geolocation"] : null), "html", null, true);
        echo "'
                });
            });
        </script>

        <div id=\"map\" class=\"map-inner\" style=\"height: ";
        // line 37
        echo twig_escape_filter($this->env, (isset($context["height"]) ? $context["height"] : null), "html", null, true);
        echo "\"></div>
        <!-- /.map-inner -->

        ";
        // line 40
        if ((isset($context["show_filter"]) ? $context["show_filter"] : null)) {
            // line 41
            echo "            ";
            if ((isset($context["horizontal_filter"]) ? $context["horizontal_filter"] : null)) {
                // line 42
                echo "                <div class=\"container\">
                    <div class=\"row\">
                        <div class=\"span12\">
                            ";
                // line 45
                $this->env->loadTemplate("properties/filter-horizontal.twig")->display($context);
                // line 46
                echo "                        </div>
                        <!-- /.span12 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container -->
            ";
            }
            // line 52
            echo "        ";
        }
        // line 53
        echo "    </div>
    <!-- /.map -->
</div><!-- /.map-wrapper -->";
    }

    public function getTemplateName()
    {
        return "properties/map.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  125 => 53,  122 => 52,  114 => 46,  112 => 45,  107 => 42,  104 => 41,  102 => 40,  96 => 37,  88 => 32,  82 => 29,  78 => 28,  73 => 26,  69 => 25,  65 => 24,  61 => 23,  57 => 22,  53 => 21,  45 => 15,  42 => 14,  34 => 8,  32 => 7,  27 => 4,  24 => 3,  22 => 2,  19 => 1,);
    }
}
