<?php

/* helpers/palette.twig */
class __TwigTemplate_8744710449aca97a11018022ca8f0488 extends Twig_Template
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
        echo "<div class=\"palette\">
    <div class=\"toggle\">
        <a href=\"#\">";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Toggle", 1 => "aviators"), "method"), "html", null, true);
        echo "</a>
    </div><!-- /.toggle -->

    <div class=\"inner\">
        <div class=\"headers\">
            <h2>";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Header styles", 1 => "aviators"), "method"), "html", null, true);
        echo "</h2>
            <ul>
                <li><a class=\"header-light\">";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Light", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a class=\"header-normal\">";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Normal", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a class=\"header-dark\">";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Dark", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
            </ul>
        </div><!-- /.headers -->

        <div class=\"patterns\">
            <h2>";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Patterns", 1 => "aviators"), "method"), "html", null, true);
        echo "</h2>
            <ul>
                <li><a class=\"pattern-cloth-alike\">cloth-alike</a></li>
                <li><a class=\"pattern-corrugation\">corrugation</a></li>
                <li><a class=\"pattern-diagonal-noise\">diagonal-noise</a></li>
                <li><a class=\"pattern-dust\">dust</a></li>
                <li><a class=\"pattern-fabric-plaid\">fabric-plaid</a></li>
                <li><a class=\"pattern-farmer\">farmer</a></li>
                <li><a class=\"pattern-grid-noise\">grid-noise</a></li>
                <li><a class=\"pattern-lghtmesh\">lghtmesh</a></li>
                <li><a class=\"pattern-pw-maze-white\">pw-maze-white</a></li>
                <li><a class=\"pattern-none\">none</a></li>
            </ul>
        </div>

        <div class=\"colors\">
            <h2>";
        // line 33
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Color variants", 1 => "aviators"), "method"), "html", null, true);
        echo "</h2>
            <ul>
                <li><a href=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-red.css\" class=\"red\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Red", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 36
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-magenta.css\" class=\"magenta\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Magenta", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-brown.css\" class=\"brown\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Brown", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-orange.css\" class=\"orange\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Orange", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-brown-dark.css\" class=\"brown-dark\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Brown dark", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>

                <li><a href=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-red.css\" class=\"gray-red\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Red", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 42
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-magenta.css\" class=\"gray-magenta\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Magenta", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-brown.css\" class=\"gray-brown\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Brown", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-orange.css\" class=\"gray-orange\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Orange", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-brown-dark.css\" class=\"gray-brown-dark\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Brown dark", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>

                <li><a href=\"";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-green-light.css\" class=\"green-light\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Green light", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 48
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-green.css\" class=\"green\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Green", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 49
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-turquiose.css\" class=\"turquiose\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Turquiose", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-blue.css\" class=\"blue\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Blue", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-violet.css\" class=\"violet\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Violet", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>

                <li><a href=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-green-light.css\" class=\"gray-green-light\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Green light", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 54
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-green.css\" class=\"gray-green\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Green", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-turquiose.css\" class=\"gray-turquiose\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Turquiose", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 56
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-blue.css\" class=\"gray-blue\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Blue", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
                <li><a href=\"";
        // line 57
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
        echo "/assets/css/realia-gray-violet.css\" class=\"gray-violet\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Gray Violet", 1 => "aviators"), "method"), "html", null, true);
        echo "</a></li>
            </ul>
        </div><!-- /.colors -->

        <div class=\"layouts clearfix\">
            <h2>";
        // line 62
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Layouts", 1 => "aviators"), "method"), "html", null, true);
        echo "</h2>
            <p>
                <label>
                    <input type=\"radio\" name=\"layout\" value=\"wide\" checked=\"checked\"> ";
        // line 65
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Wide", 1 => "aviators"), "method"), "html", null, true);
        echo "
                </label>
            </p>

            <p>
                <label>
                    <input type=\"radio\" name=\"layout\" value=\"boxed\"> ";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Boxed", 1 => "aviators"), "method"), "html", null, true);
        echo "
                </label>
            </p>
        </div><!-- /.layouts -->

        <a href=\"#\" class=\"btn btn-primary reset\">";
        // line 76
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Reset default", 1 => "aviators"), "method"), "html", null, true);
        echo "</a>
    </div><!-- /.inner -->
</div><!-- /.palette -->";
    }

    public function getTemplateName()
    {
        return "helpers/palette.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  226 => 76,  218 => 71,  209 => 65,  203 => 62,  181 => 55,  175 => 54,  169 => 53,  150 => 49,  144 => 48,  138 => 47,  131 => 45,  125 => 44,  119 => 43,  113 => 42,  100 => 39,  94 => 38,  88 => 37,  82 => 36,  44 => 12,  40 => 11,  36 => 10,  213 => 96,  210 => 95,  201 => 89,  198 => 88,  196 => 87,  193 => 57,  190 => 85,  187 => 56,  185 => 83,  173 => 73,  167 => 71,  165 => 70,  162 => 51,  156 => 50,  154 => 66,  151 => 65,  145 => 63,  143 => 62,  140 => 61,  134 => 59,  132 => 58,  129 => 57,  123 => 55,  121 => 54,  118 => 53,  112 => 50,  109 => 49,  107 => 41,  98 => 42,  91 => 37,  84 => 32,  78 => 30,  76 => 35,  71 => 33,  65 => 24,  63 => 23,  58 => 20,  52 => 17,  50 => 17,  45 => 14,  39 => 12,  37 => 11,  31 => 8,  29 => 6,  23 => 3,  19 => 1,);
    }
}
