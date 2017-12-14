<?php

/* properties/filter-horizontal.twig */
class __TwigTemplate_45f4304cf82c2d7125e9e03b88abef99 extends Twig_Template
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
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 2
            echo "    ";
            echo (isset($context["before_title"]) ? $context["before_title"] : null);
            echo (isset($context["title"]) ? $context["title"] : null);
            echo (isset($context["after_title"]) ? $context["after_title"] : null);
            echo "
";
        }
        // line 4
        echo "
<div class=\"property-filter widget filter-horizontal\">
    <div class=\"content\">
        ";
        // line 7
        if ((isset($context["map_filtering"]) ? $context["map_filtering"] : null)) {
            // line 8
            echo "        <form method=\"get\" action=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
            echo "/aviators/plugins/properties/filter.php\" class=\"form-inline map-filtering\">
            ";
        } else {
            // line 10
            echo "            <form method=\"get\" action=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_get_home_url", array(), "method"), "html", null, true);
            echo "/";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "properties", 1 => "aviators"), "method"), "html", null, true);
            echo "/\" class=\"form-inline\">
                ";
        }
        // line 12
        echo "
                ";
        // line 13
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_type"), "method") != "on")) {
            // line 14
            echo "                    <div class=\"property-types\">
                        ";
            // line 15
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_terms", array(0 => "property_types"), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
                // line 16
                echo "                            <div class=\"property-type ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "slug"), "html", null, true);
                echo " ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_type"));
                foreach ($context['_seq'] as $context["_key"] => $context["filter_type"]) {
                    if (((isset($context["filter_type"]) ? $context["filter_type"] : null) == $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "term_id"))) {
                        echo "active";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['filter_type'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                echo "\">
                                <label for=\"filter_type_";
                // line 17
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "term_id"), "html", null, true);
                echo "\">
                                    <input type=\"checkbox\" name=\"filter_type[]\" title=\"";
                // line 18
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "name"), "html", null, true);
                echo "\" id=\"filter_type_";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "term_id"), "html", null, true);
                echo "\" class=\"no-ezmark\" value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "term_id"), "html", null, true);
                echo "\" ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_type"));
                foreach ($context['_seq'] as $context["_key"] => $context["filter_type"]) {
                    if (((isset($context["filter_type"]) ? $context["filter_type"] : null) == $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "term_id"))) {
                        echo "checked";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['filter_type'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                echo "> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["type"]) ? $context["type"] : null), "name"), "html", null, true);
                echo "
                                </label>
                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['type'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 22
            echo "                    </div>
                    <!-- /.property-types -->
                ";
        }
        // line 25
        echo "                <div class=\"general\">
                    ";
        // line 26
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_location"), "method") != "on")) {
            // line 27
            echo "                        <select name=\"filter_location\" id=\"inputLocation\" class=\"location\">
                            <option value=\"\">";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Location", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 29
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_terms", array(0 => "locations", 1 => array("parent" => 0)), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["location"]) {
                // line 30
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "term_id"), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_location") == $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "term_id"))) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "name"), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['location'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 32
            echo "                        </select>
                    ";
        }
        // line 34
        echo "
                    ";
        // line 35
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_sublocation"), "method") != "on")) {
            // line 36
            echo "                        ";
            if ($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_location")) {
                // line 37
                echo "                            ";
                $context["sublocations"] = $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_terms", array(0 => "locations", 1 => array("parent" => $this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_location"))), "method");
                // line 38
                echo "                        ";
            }
            // line 39
            echo "
                        <select name=\"filter_sublocation\" id=\"filter_sublocation\" class=\"location\">
                            <option value=\"\">";
            // line 41
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Sublocation", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 42
            if ((isset($context["sublocations"]) ? $context["sublocations"] : null)) {
                // line 43
                echo "                                ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["sublocations"]) ? $context["sublocations"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["location"]) {
                    // line 44
                    echo "                                    <option value=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "term_id"), "html", null, true);
                    echo "\" ";
                    if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_sublocation") == $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "term_id"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "name"), "html", null, true);
                    echo "</option>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['location'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 46
                echo "                            ";
            }
            // line 47
            echo "                        </select>
                    ";
        }
        // line 49
        echo "
                    ";
        // line 50
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_sub_sublocation"), "method") != "on")) {
            // line 51
            echo "                        ";
            if ($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_location")) {
                // line 52
                echo "                            ";
                $context["sublocations"] = $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_terms", array(0 => "locations", 1 => array("parent" => $this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_sublocation"))), "method");
                // line 53
                echo "                        ";
            }
            // line 54
            echo "                        <select name=\"filter_sub_sublocation\" id=\"filter_sub_sublocation\" class=\"location\">
                            <option value=\"\">";
            // line 55
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "District", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 56
            if ((isset($context["sublocations"]) ? $context["sublocations"] : null)) {
                // line 57
                echo "                                ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["sublocations"]) ? $context["sublocations"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["location"]) {
                    // line 58
                    echo "                                    <option value=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "term_id"), "html", null, true);
                    echo "\" ";
                    if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_sub_sublocation") == $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "term_id"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["location"]) ? $context["location"] : null), "name"), "html", null, true);
                    echo "</option>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['location'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 60
                echo "                            ";
            }
            // line 61
            echo "                        </select>
                    ";
        }
        // line 63
        echo "
                    ";
        // line 64
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_contract"), "method") != "on")) {
            // line 65
            echo "                        <select name=\"filter_contract_type\" id=\"inputContractType\" class=\"contract-type\">
                            <option value=\"\">";
            // line 66
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Contract", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 67
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_terms", array(0 => "property_contracts"), "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["contract"]) {
                // line 68
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["contract"]) ? $context["contract"] : null), "term_id"), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_contract_type") == $this->getAttribute((isset($context["contract"]) ? $context["contract"] : null), "term_id"))) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["contract"]) ? $context["contract"] : null), "name"), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['contract'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 70
            echo "                        </select>
                    ";
        }
        // line 72
        echo "
                    ";
        // line 73
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_beds"), "method") != "on")) {
            // line 74
            echo "                        <select name=\"filter_bedrooms\" id=\"inputBeds-";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\" class=\"beds\">
                            <option value=\"\">";
            // line 75
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Beds", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 76
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, 10));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 77
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_bedrooms") == (isset($context["i"]) ? $context["i"] : null))) {
                    echo "selected=\"selected=\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 79
            echo "                        </select>
                    ";
        }
        // line 81
        echo "
                    ";
        // line 82
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_baths"), "method") != "on")) {
            // line 83
            echo "                        <select name=\"filter_bathrooms\" id=\"inputBaths-";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\" class=\"baths\">
                            <option value=\"\">";
            // line 84
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Baths", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 85
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, 10));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 86
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_bathrooms") == (isset($context["i"]) ? $context["i"] : null))) {
                    echo "selected=\"selected=\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 88
            echo "                        </select>
                    ";
        }
        // line 90
        echo "
                    ";
        // line 91
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_price_from"), "method") != "on")) {
            // line 92
            echo "                        <select name=\"filter_price_from\" id=\"inputPriceFrom-";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\" class=\"price-from\">
                            <option value=\"\">";
            // line 93
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Price From", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 94
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["price_from"]) ? $context["price_from"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 95
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_price_from") == (isset($context["i"]) ? $context["i"] : null))) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_price_format", array(0 => (isset($context["i"]) ? $context["i"] : null)), "method"), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 97
            echo "                        </select>
                    ";
        }
        // line 99
        echo "
                    ";
        // line 100
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_price_to"), "method") != "on")) {
            // line 101
            echo "                        <select name=\"filter_price_to\" id=\"inputPriceTo-";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\" class=\"price-to\">
                            <option value=\"\">";
            // line 102
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Price To", 1 => "aviators"), "method"), "html", null, true);
            echo "</option>
                            ";
            // line 103
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["price_to"]) ? $context["price_to"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 104
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_price_to") == (isset($context["i"]) ? $context["i"] : null))) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_price_format", array(0 => (isset($context["i"]) ? $context["i"] : null)), "method"), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 106
            echo "                        </select>
                    ";
        }
        // line 108
        echo "
                    ";
        // line 109
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_area_from"), "method") != "on")) {
            // line 110
            echo "                        <input type=\"text\" value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_area_from"), "html", null, true);
            echo "\" name=\"filter_area_from\" id=\"inputAreaFrom-";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\" placeholder=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Area from", 1 => "aviators"), "method"), "html", null, true);
            echo "\">
                    ";
        }
        // line 112
        echo "
                    ";
        // line 113
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "filter_visibility_h", 2 => "hide_area_to"), "method") != "on")) {
            // line 114
            echo "                        <input type=\"text\" value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["q"]) ? $context["q"] : null), "filter_area_to"), "html", null, true);
            echo "\" name=\"filter_area_to\" id=\"inputAreaTo-";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\" placeholder=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Area to", 1 => "aviators"), "method"), "html", null, true);
            echo "\">
                    ";
        }
        // line 116
        echo "
                    <button class=\"btn btn-primary btn-large\">";
        // line 117
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Filter now!", 1 => "aviators"), "method"), "html", null, true);
        echo "</button>
                </div><!-- /.general -->
            </form>
    </div>
    <!-- /.content -->
</div><!-- /.property-filter -->";
    }

    public function getTemplateName()
    {
        return "properties/filter-horizontal.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  466 => 117,  463 => 116,  453 => 114,  451 => 113,  448 => 112,  438 => 110,  436 => 109,  433 => 108,  429 => 106,  414 => 104,  410 => 103,  406 => 102,  401 => 101,  399 => 100,  396 => 99,  392 => 97,  377 => 95,  373 => 94,  369 => 93,  364 => 92,  362 => 91,  359 => 90,  355 => 88,  340 => 86,  336 => 85,  332 => 84,  327 => 83,  325 => 82,  322 => 81,  318 => 79,  303 => 77,  299 => 76,  295 => 75,  290 => 74,  288 => 73,  285 => 72,  281 => 70,  266 => 68,  262 => 67,  258 => 66,  255 => 65,  253 => 64,  250 => 63,  246 => 61,  243 => 60,  228 => 58,  223 => 57,  221 => 56,  217 => 55,  214 => 54,  211 => 53,  208 => 52,  205 => 51,  203 => 50,  200 => 49,  196 => 47,  193 => 46,  178 => 44,  173 => 43,  171 => 42,  167 => 41,  163 => 39,  160 => 38,  157 => 37,  154 => 36,  152 => 35,  149 => 34,  145 => 32,  130 => 30,  126 => 29,  119 => 27,  117 => 26,  109 => 22,  62 => 16,  58 => 15,  55 => 14,  50 => 12,  36 => 8,  29 => 4,  91 => 26,  81 => 23,  75 => 22,  68 => 19,  64 => 18,  51 => 14,  38 => 9,  25 => 4,  21 => 2,  125 => 53,  122 => 28,  114 => 25,  112 => 45,  107 => 42,  104 => 41,  102 => 40,  96 => 37,  88 => 32,  82 => 18,  78 => 17,  73 => 26,  69 => 25,  65 => 24,  61 => 23,  57 => 22,  53 => 13,  45 => 10,  42 => 10,  34 => 7,  32 => 8,  27 => 4,  24 => 3,  22 => 2,  19 => 1,);
    }
}
