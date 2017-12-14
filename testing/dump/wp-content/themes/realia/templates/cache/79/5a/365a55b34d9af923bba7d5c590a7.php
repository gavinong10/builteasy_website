<?php

/* properties/property-box-small.twig */
class __TwigTemplate_795a365a55b34d9af923bba7d5c590a7 extends Twig_Template
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
        echo "<div class=\"property\">
    <div class=\"image\">
        <div class=\"content\">
            <a href=\"";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_permalink", array(0 => $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "ID")), "method"), "html", null, true);
        echo "\"></a>

            ";
        // line 6
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_the_post_thumbnail", array(0 => $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "ID")), "method")) {
            // line 7
            echo "                <img src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aq_resize", array(0 => $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "wp_get_attachment_url", array(0 => $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_post_thumbnail_id", array(0 => $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "ID")), "method"), 1 => "full"), "method"), 1 => 270, 2 => 200, 3 => "true"), "method"), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "post_title"), "html", null, true);
            echo "\">
            ";
        } else {
            // line 9
            echo "                <img src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
            echo "/assets/img/property-tmp-small.png\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "post_title"), "html", null, true);
            echo "\">
            ";
        }
        // line 11
        echo "        </div><!-- /.content -->

        ";
        // line 13
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "common", 2 => "disable_contract_type_label"), "method") != "on")) {
            // line 14
            echo "            ";
            if ($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "property_contracts"), 0)) {
                // line 15
                echo "                <div class=\"rent-sale\">
                    ";
                // line 16
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "property_contracts"), 0), "name"), "html", null, true);
                echo "
                </div><!-- /.rent-sale -->
            ";
            }
            // line 19
            echo "        ";
        }
        // line 20
        echo "
        <div class=\"price\">
            ";
        // line 22
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_custom_text"), 0)) {
            // line 23
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_custom_text"), 0), "html", null, true);
            echo "
            ";
        } else {
            // line 25
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_price_format", array(0 => $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_price"), 0)), "method"), "html", null, true);
            echo "

                ";
            // line 27
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_price_suffix"), 0)) {
                // line 28
                echo "                    <span class=\"suffix\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_price_suffix"), 0), "html", null, true);
                echo "</span>
                ";
            }
            // line 30
            echo "            ";
        }
        // line 31
        echo "        </div><!-- /.price -->

        ";
        // line 33
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_reduced"), 0)) {
            // line 34
            echo "            <div class=\"reduced\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Reduced", 1 => "aviators"), "method"), "html", null, true);
            echo "</div><!-- /.reduced -->
        ";
        }
        // line 36
        echo "    </div>
    <!-- /.image -->

    <div class=\"title\">
        <h2><a href=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_permalink", array(0 => $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "ID")), "method"), "html", null, true);
        echo "\">
                ";
        // line 41
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_title"), 0)) {
            // line 42
            echo "                    ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_title"), 0), "html", null, true);
            echo "
                ";
        } else {
            // line 44
            echo "                    ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : null), "post_title"), "html", null, true);
            echo "
                ";
        }
        // line 46
        echo "            </a></h2>
    </div>
    <!-- /.title -->

    <div class=\"location\">";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "location"), 0), "name"), "html", null, true);
        echo "</div>
    <!-- /.location -->

    ";
        // line 53
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_area"), 0)) {
            // line 54
            echo "        <div class=\"area\">
            <span class=\"key\">";
            // line 55
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Area", 1 => "aviators"), "method"), "html", null, true);
            echo ":</span><!-- /.key -->
            <span class=\"value\">";
            // line 56
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_area"), 0), "html", null, true);
            echo $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "units", 2 => "area"), "method");
            echo "</span><!-- /.value -->
        </div><!-- /.area -->
    ";
        }
        // line 59
        echo "
    ";
        // line 60
        if (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_hide_baths"), 0) != "1")) {
            // line 61
            echo "        ";
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_bathrooms"), 0)) {
                // line 62
                echo "            <div class=\"bathrooms\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Bathrooms", 1 => "aviators"), "method"), "html", null, true);
                echo "\">
                <div class=\"content\">";
                // line 63
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_bathrooms"), 0), "html", null, true);
                echo "</div>
            </div><!-- /.bathrooms -->
        ";
            }
            // line 66
            echo "    ";
        }
        // line 67
        echo "
    ";
        // line 68
        if (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_hide_beds"), 0) != "1")) {
            // line 69
            echo "        ";
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_bedrooms"), 0)) {
                // line 70
                echo "            <div class=\"bedrooms\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Bedrooms", 1 => "aviators"), "method"), "html", null, true);
                echo "\">
                <div class=\"content\">";
                // line 71
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["property"]) ? $context["property"] : null), "meta"), "_property_bedrooms"), 0), "html", null, true);
                echo "</div>
            </div><!-- /.bedrooms -->
        ";
            }
            // line 74
            echo "    ";
        }
        // line 75
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "properties/property-box-small.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  205 => 75,  202 => 74,  196 => 71,  191 => 70,  188 => 69,  186 => 68,  183 => 67,  180 => 66,  174 => 63,  169 => 62,  166 => 61,  164 => 60,  161 => 59,  154 => 56,  150 => 55,  147 => 54,  145 => 53,  139 => 50,  133 => 46,  127 => 44,  121 => 42,  119 => 41,  115 => 40,  109 => 36,  103 => 34,  94 => 30,  88 => 28,  86 => 27,  80 => 25,  74 => 23,  72 => 22,  68 => 20,  65 => 19,  59 => 16,  56 => 15,  51 => 13,  101 => 33,  97 => 31,  90 => 19,  75 => 17,  73 => 16,  70 => 15,  53 => 14,  47 => 11,  43 => 10,  39 => 9,  31 => 7,  29 => 6,  26 => 4,  24 => 4,  19 => 1,);
    }
}
