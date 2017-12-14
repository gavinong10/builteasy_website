<?php

/* helpers/footer.twig */
class __TwigTemplate_6c4cecdd46030d67514f6e32266e6ea9 extends Twig_Template
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
        echo "            </div><!-- /#wrapper-inner -->

            ";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "bottom"), "method"), "html", null, true);
        echo "

            <div id=\"footer-wrapper\">
                ";
        // line 6
        if (((($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "footer-1"), "method") || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "footer-2"), "method")) || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "footer-3"), "method")) || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_active_sidebar", array(0 => "footer-4"), "method"))) {
            // line 7
            echo "                    <div id=\"footer-top\">
                        <div id=\"footer-top-inner\" class=\"container\">
                            <div class=\"row\">
                                <div class=\"span3\">
                                    ";
            // line 11
            if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "dynamic_sidebar", array(0 => "footer-1"), "method"))) {
                // line 12
                echo "                                        ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "footer-1"), "method"), "html", null, true);
                echo "
                                    ";
            }
            // line 14
            echo "                                </div>

                                <div class=\"span3\">
                                    ";
            // line 17
            if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "dynamic_sidebar", array(0 => "footer-2"), "method"))) {
                // line 18
                echo "                                        ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "footer-2"), "method"), "html", null, true);
                echo "
                                    ";
            }
            // line 20
            echo "                                </div>

                                <div class=\"span3\">
                                    ";
            // line 23
            if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "dynamic_sidebar", array(0 => "footer-3"), "method"))) {
                // line 24
                echo "                                        ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "footer-3"), "method"), "html", null, true);
                echo "
                                    ";
            }
            // line 26
            echo "                                </div>

                                <div class=\"span3\">
                                    ";
            // line 29
            if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "dynamic_sidebar", array(0 => "footer-4"), "method"))) {
                // line 30
                echo "                                        ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "footer-4"), "method"), "html", null, true);
                echo "
                                    ";
            }
            // line 32
            echo "                                </div>
                            </div><!-- /.row -->
                        </div><!-- /#footer-top-inner -->
                    </div><!-- /#footer-top -->
                ";
        }
        // line 37
        echo "
                <div id=\"footer\" class=\"footer container\">
                    <div id=\"footer-inner\">
                        <div class=\"row\">
                            <div class=\"span6 copyright\">
                                ";
        // line 42
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_sidebar", array(0 => "footer-bottom-left"), "method"), "html", null, true);
        echo "
                            </div><!-- /.copyright -->

                            <div class=\"span6 share\">
                                <div class=\"content\">
                                    <ul class=\"menu nav\">
                                        ";
        // line 48
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "facebook", 2 => "is_hidden"), "method"))) {
            // line 49
            echo "                                            <li class=\"first leaf\">
                                                <a href=\"";
            // line 50
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "facebook", 2 => "link"), "method"), "html", null, true);
            echo "\" class=\"facebook\">Facebook</a>
                                            </li>
                                        ";
        }
        // line 53
        echo "
                                        ";
        // line 54
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "flickr", 2 => "is_hidden"), "method"))) {
            // line 55
            echo "                                            <li class=\"leaf\"><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "flickr", 2 => "link"), "method"), "html", null, true);
            echo "\" class=\"flickr\">Flickr</a></li>
                                        ";
        }
        // line 57
        echo "
                                        ";
        // line 58
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "google_plus", 2 => "is_hidden"), "method"))) {
            // line 59
            echo "                                            <li class=\"leaf\"><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "google_plus", 2 => "link"), "method"), "html", null, true);
            echo "\" class=\"google\">Google+</a></li>
                                        ";
        }
        // line 61
        echo "
                                        ";
        // line 62
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "linkedin", 2 => "is_hidden"), "method"))) {
            // line 63
            echo "                                            <li class=\"leaf\"><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "linkedin", 2 => "link"), "method"), "html", null, true);
            echo "\" class=\"linkedin\">LinkedIn</a></li>
                                        ";
        }
        // line 65
        echo "
                                        ";
        // line 66
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "twitter", 2 => "is_hidden"), "method"))) {
            // line 67
            echo "                                            <li class=\"leaf\"><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "twitter", 2 => "link"), "method"), "html", null, true);
            echo "\" class=\"twitter\">Twitter</a></li>
                                        ";
        }
        // line 69
        echo "
                                        ";
        // line 70
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "vimeo", 2 => "is_hidden"), "method"))) {
            // line 71
            echo "                                            <li class=\"last leaf\"><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "socialize", 1 => "vimeo", 2 => "link"), "method"), "html", null, true);
            echo "\" class=\"vimeo\">Vimeo</a></li>
                                        ";
        }
        // line 73
        echo "                                    </ul>
                                </div><!-- /.content -->
                            </div><!-- /.span6 -->
                        </div><!-- /.row -->
                    </div><!-- /#footer-inner -->
                </div><!-- /#footer -->
            </div><!-- /#footer-wrapper -->
        </div><!-- /#wrapper-outer -->
    </div><!-- /#wrapper -->

    ";
        // line 83
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_theme_mod", array(0 => "general_palette_is_hidden"), "method") == "")) {
            // line 84
            echo "        ";
            $this->env->loadTemplate("helpers/palette.twig")->display($context);
            // line 85
            echo "    ";
        }
        // line 86
        echo "
    ";
        // line 87
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "google_analytics", 1 => "tracking", 2 => "code"), "method")) {
            // line 88
            echo "        <script type=\"text/javascript\">
            var _gaq=[[\"_setAccount\", \"";
            // line 89
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "google_analytics", 1 => "tracking", 2 => "code"), "method"), "html", null, true);
            echo "\"],[\"_trackPageview\"]];
            (function(d,t){ var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
                g.src=\"//www.google-analytics.com/ga.js\";
                s.parentNode.insertBefore(g,s) }(document,\"script\"));
        </script>
    ";
        }
        // line 95
        echo "
    ";
        // line 96
        echo twig_escape_filter($this->env, aviators_templates_helpers_wp_footer(), "html", null, true);
        echo "
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "helpers/footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  213 => 96,  210 => 95,  201 => 89,  198 => 88,  196 => 87,  193 => 86,  190 => 85,  187 => 84,  185 => 83,  173 => 73,  167 => 71,  165 => 70,  162 => 69,  156 => 67,  154 => 66,  151 => 65,  145 => 63,  143 => 62,  140 => 61,  134 => 59,  132 => 58,  129 => 57,  123 => 55,  121 => 54,  118 => 53,  112 => 50,  109 => 49,  107 => 48,  98 => 42,  91 => 37,  84 => 32,  78 => 30,  76 => 29,  71 => 26,  65 => 24,  63 => 23,  58 => 20,  52 => 18,  50 => 17,  45 => 14,  39 => 12,  37 => 11,  31 => 7,  29 => 6,  23 => 3,  19 => 1,);
    }
}
