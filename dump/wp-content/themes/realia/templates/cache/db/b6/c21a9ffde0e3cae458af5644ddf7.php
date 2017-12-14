<?php

/* helpers/header.twig */
class __TwigTemplate_dbb6c21a9ffde0e3cae458af5644ddf7 extends Twig_Template
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
        echo "<!DOCTYPE html>
<!--[if IE 7]>
<html class=\"ie ie7\" ";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "language_attributes", array(), "method"), "html", null, true);
        echo ">
<![endif]-->
<!--[if IE 8]>
<html class=\"ie ie8\" ";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "language_attributes", array(), "method"), "html", null, true);
        echo ">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html ";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "language_attributes", array(), "method"), "html", null, true);
        echo ">
<!--<![endif]-->

<head>
    <meta charset=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "bloginfo", array(0 => "charset"), "method"), "html", null, true);
        echo "\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta name=\"author\" content=\"Aviators, http://themes.byaviators.com\">

    <link rel=\"shortcut icon\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_stylesheet_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/img/favicon.png\" type=\"image/png\">
    <link rel=\"profile\" href=\"http://gmpg.org/xfn/11\">
    <link rel=\"pingback\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "bloginfo", array(0 => "pingback_url"), "method"), "html", null, true);
        echo "\">

    <!--[if lt IE 9]>
        <script src=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/js/html5.js\" type=\"text/javascript\"></script>
    <![endif]-->

    ";
        // line 25
        echo twig_escape_filter($this->env, aviators_templates_helpers_wp_head(), "html", null, true);
        echo "

    ";
        // line 27
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_singular", array(), "method")) {
            // line 28
            echo "        ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "wp_enqueue_script", array(0 => "comment-reply"), "method"), "html", null, true);
            echo "
    ";
        }
        // line 30
        echo "
    ";
        // line 31
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "general_palette_is_hidden"), "method") == "")) {
            // line 32
            echo "        <link rel=\"stylesheet\" href=\"#\" type=\"text/css\" id=\"color-variant\">
    ";
        }
        // line 34
        echo "
    <title>";
        // line 35
        echo $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "wp_title", array(0 => "|", 1 => false, 2 => "right"), "method");
        echo "</title>
</head>

<body class=\"";
        // line 38
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_body_class"));
        foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
            echo twig_escape_filter($this->env, (isset($context["class"]) ? $context["class"] : null), "html", null, true);
            echo " ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_variant"), "method"), "html", null, true);
        echo " color-";
        echo twig_escape_filter($this->env, (isset($context["color_class"]) ? $context["color_class"] : null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "general_pattern"), "method"), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "general_layout"), "method"), "html", null, true);
        echo " ";
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_position_is_fixed"), "method")) {
            echo "header-fixed";
        }
        echo "\">

<div id=\"wrapper-outer\">
    <div id=\"wrapper\">
        <div id=\"wrapper-inner\">
            <div class=\"header-top-wrapper\">
                <div class=\"header-top\">
                    ";
        // line 45
        $this->env->loadTemplate("helpers/topbar.twig")->display($context);
        // line 46
        echo "
                    <!-- HEADER -->
                    <div id=\"header-wrapper\">
                        <div id=\"header\">
                            <div id=\"header-inner\">
                                <div class=\"container\">
                                    <div class=\"navbar\">
                                        <div class=\"navbar-inner\">
                                            <div class=\"row\">
                                                <div class=\"logo-wrapper span4\">
                                                    <a href=\"#nav\" id=\"btn-nav\">";
        // line 56
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Toggle navigation", 1 => "aviators"), "method"), "html", null, true);
        echo "</a>

                                                    ";
        // line 59
        echo "                                                    <div class=\"logo\">
                                                        ";
        // line 60
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "defined", array(0 => "ICL_LANGUAGE_CODE"), "method")) {
            // line 61
            echo "                                                            <a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "icl_get_home_url", array(), "method"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Home", 1 => "aviators"), "method"), "html", null, true);
            echo "\">
                                                        ";
        } else {
            // line 63
            echo "                                                            <a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_bloginfo", array(0 => "wpurl"), "method"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Home", 1 => "aviators"), "method"), "html", null, true);
            echo "\">
                                                        ";
        }
        // line 65
        echo "                                                            ";
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_logo"), "method")) {
            // line 66
            echo "                                                                <img src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_logo"), "method"), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Home", 1 => "aviators"), "method"), "html", null, true);
            echo "\">
                                                            ";
        } else {
            // line 68
            echo "                                                                <img src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
            echo "/assets/img/logo.png\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Home", 1 => "aviators"), "method"), "html", null, true);
            echo "\">
                                                            ";
        }
        // line 70
        echo "                                                        </a>
                                                    </div><!-- /.logo -->

                                                    ";
        // line 74
        echo "                                                    ";
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_title_is_hidden"), "method") == "")) {
            // line 75
            echo "                                                        ";
            if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_bloginfo", array(0 => "name"), "method")) {
                // line 76
                echo "                                                            <div class=\"site-name\">
                                                                <a href=\"";
                // line 77
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_bloginfo", array(0 => "wpurl"), "method"), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Home"), "method"), "html", null, true);
                echo "\" class=\"brand\">
                                                                    ";
                // line 78
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_bloginfo", array(0 => "name"), "method"), "html", null, true);
                echo "
                                                                </a>
                                                            </div><!-- /.site-name -->
                                                        ";
            }
            // line 82
            echo "                                                    ";
        }
        // line 83
        echo "
                                                    ";
        // line 85
        echo "                                                    ";
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_description_is_hidden"), "method") == "")) {
            // line 86
            echo "                                                        ";
            if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_bloginfo", array(0 => "description"), "method")) {
                // line 87
                echo "                                                            <div class=\"site-slogan\">
                                                                <span>";
                // line 88
                echo $this->env->getExtension('html_decode_twig_extension')->htmldecode($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_bloginfo", array(0 => "description"), "method"));
                echo "</span>
                                                            </div><!-- /.site-slogan -->
                                                        ";
            }
            // line 91
            echo "                                                    ";
        }
        // line 92
        echo "                                                </div><!-- /.logo-wrapper -->

                                                ";
        // line 94
        if ((($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_email_is_hidden"), "method") == "") || ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_phone_is_hidden"), "method") == ""))) {
            // line 95
            echo "                                                    <div class=\"info\">
                                                        ";
            // line 97
            echo "                                                        ";
            if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_email_is_hidden"), "method") == "")) {
                // line 98
                echo "                                                            <div class=\"site-email\">
                                                                <a href=\"mailto:";
                // line 99
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_option", array(0 => "header_email", 1 => "info@byaviators.com"), "method"), "html", null, true);
                echo "\">
                                                                    ";
                // line 100
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_option", array(0 => "header_email", 1 => "info@byaviators.com"), "method"), "html", null, true);
                echo "
                                                                </a>
                                                            </div><!-- /.site-email -->
                                                        ";
            }
            // line 104
            echo "
                                                        ";
            // line 106
            echo "                                                        ";
            if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_phone_is_hidden"), "method") == "")) {
                // line 107
                echo "                                                            <div class=\"site-phone\">
                                                                <span>";
                // line 108
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_option", array(0 => "header_phone", 1 => "333-444-555"), "method"), "html", null, true);
                echo "</span>
                                                            </div><!-- /.site-phone -->
                                                        ";
            }
            // line 111
            echo "                                                    </div><!-- /.info -->
                                                ";
        }
        // line 113
        echo "
                                                ";
        // line 115
        echo "                                                ";
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "header_call_to_action_is_hidden"), "method") == "")) {
            // line 116
            echo "
                                                    <a class=\"btn btn-primary btn-large list-your-property arrow-right\"
                                                        href=\"";
            // line 118
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_property_submission_button_get", array(0 => "url"), "method"), "html", null, true);
            echo "\">
                                                        ";
            // line 119
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_property_submission_button_get", array(0 => "text"), "method"), "html", null, true);
            echo "
                                                    </a>
                                                ";
        }
        // line 122
        echo "                                            </div><!-- /.row -->
                                        </div><!-- /.navbar-inner -->
                                    </div><!-- /.navbar -->
                                </div><!-- /.container -->
                            </div><!-- /#header-inner -->
                        </div><!-- /#header -->
                    </div><!-- /#header-wrapper -->
                </div><!-- /.top -->
            </div><!-- /.top-wrapper -->

            <!-- NAVIGATION -->
            <div id=\"navigation\">
                <div class=\"container\">
                    <div class=\"navigation-wrapper\">
                        <div class=\"navigation clearfix-normal\">
                            ";
        // line 137
        echo (isset($context["main_menu"]) ? $context["main_menu"] : null);
        echo "

                            ";
        // line 139
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "dynamic_sidebar", array(0 => "navigation-right"), "method")) {
        }
        // line 140
        echo "                            ";
        // line 141
        echo "                                ";
        // line 142
        echo "                            ";
        // line 143
        echo "                        </div><!-- /.navigation -->
                    </div><!-- /.navigation-wrapper -->
                </div><!-- /.container -->
            </div><!-- /.navigation -->";
    }

    public function getTemplateName()
    {
        return "helpers/header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  333 => 143,  331 => 142,  329 => 141,  327 => 140,  324 => 139,  319 => 137,  302 => 122,  296 => 119,  292 => 118,  288 => 116,  285 => 115,  282 => 113,  278 => 111,  272 => 108,  269 => 107,  266 => 106,  263 => 104,  256 => 100,  252 => 99,  249 => 98,  246 => 97,  243 => 95,  241 => 94,  237 => 92,  234 => 91,  228 => 88,  225 => 87,  222 => 86,  219 => 85,  216 => 83,  213 => 82,  206 => 78,  200 => 77,  197 => 76,  194 => 75,  191 => 74,  186 => 70,  178 => 68,  170 => 66,  167 => 65,  159 => 63,  151 => 61,  149 => 60,  146 => 59,  141 => 56,  129 => 46,  127 => 45,  97 => 38,  91 => 35,  88 => 34,  84 => 32,  82 => 31,  79 => 30,  73 => 28,  71 => 27,  66 => 25,  60 => 22,  54 => 19,  49 => 17,  42 => 13,  35 => 9,  29 => 6,  23 => 3,  19 => 1,);
    }
}
