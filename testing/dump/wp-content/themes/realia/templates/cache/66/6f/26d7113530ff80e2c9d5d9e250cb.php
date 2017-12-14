<?php

/* shortcodes/content_box.twig */
class __TwigTemplate_666f26d7113530ff80e2c9d5d9e250cb extends Twig_Template
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
        echo "<div class=\"content-box\">
    <div class=\"row\">
        ";
        // line 3
        if ((isset($context["icon"]) ? $context["icon"] : null)) {
            // line 4
            echo "            <div class=\"icon span1\">
                <img src=\"";
            // line 5
            echo twig_escape_filter($this->env, (isset($context["icon"]) ? $context["icon"] : null), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
            echo "\">
            </div>
        ";
        }
        // line 8
        echo "
        ";
        // line 9
        if ((isset($context["icon_pictopro_class"]) ? $context["icon_pictopro_class"] : null)) {
            // line 10
            echo "            <div class=\"span1\">
                <div class=\"pictopro-icon\">
                    <i class=\"icon ";
            // line 12
            echo twig_escape_filter($this->env, (isset($context["icon_pictopro_class"]) ? $context["icon_pictopro_class"] : null), "html", null, true);
            echo "\"></i>
                </div>
            </div>
        ";
        }
        // line 16
        echo "
        <div class=\"content span";
        // line 17
        echo twig_escape_filter($this->env, (isset($context["columns_for_content"]) ? $context["columns_for_content"] : null), "html", null, true);
        echo "\">
            ";
        // line 18
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 19
            echo "                <h3>";
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
            echo "</h3>
            ";
        }
        // line 21
        echo "
            ";
        // line 22
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
        </div>
        <!-- /.content -->
    </div>
    <!-- /.row -->
</div><!-- /.content-box -->";
    }

    public function getTemplateName()
    {
        return "shortcodes/content_box.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 22,  67 => 21,  61 => 19,  59 => 18,  55 => 17,  52 => 16,  45 => 12,  41 => 10,  39 => 9,  36 => 8,  28 => 5,  25 => 4,  23 => 3,  19 => 1,);
    }
}
