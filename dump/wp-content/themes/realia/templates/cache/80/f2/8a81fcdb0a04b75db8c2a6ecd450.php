<?php

/* content.twig */
class __TwigTemplate_80f28a81fcdb0a04b75db8c2a6ecd450 extends Twig_Template
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
        echo "<article id=\"post-";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "the_ID", array(), "method"), "html", null, true);
        echo "\" ";
        echo twig_escape_filter($this->env, aviators_templates_helpers_post_class(), "html", null, true);
        echo ">
    ";
        // line 3
        echo "    <header class=\"entry-header\">

        ";
        // line 5
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_entry_meta", array(), "method") || $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_edit_post_link", array(), "method"))) {
            // line 6
            echo "            <div class=\"entry-meta\">
                ";
            // line 7
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_entry_meta", array(), "method"), "html", null, true);
            echo "
                ";
            // line 8
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_edit_post_link", array(), "method"), "html", null, true);
            echo "
            </div><!-- .entry-meta -->
        ";
        }
        // line 11
        echo "
        ";
        // line 12
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_single", array(), "method")) {
            // line 13
            echo "            <h1 class=\"page-header entry-title\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "the_title", array(), "method"), "html", null, true);
            echo "</h1>
        ";
        } else {
            // line 15
            echo "            <h1 class=\"page-header entry-title\">
                <a href=\"";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "the_permalink", array(), "method"), "html", null, true);
            echo "\" rel=\"bookmark\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "the_title", array(), "method"), "html", null, true);
            echo "</a>
            </h1>
        ";
        }
        // line 19
        echo "
        ";
        // line 21
        echo "        ";
        if (($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "has_post_thumbnail", array(), "method") && (!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "post_password_required", array(), "method")))) {
            // line 22
            echo "            <div class=\"entry-thumbnail\">
                ";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "the_post_thumbnail", array(), "method"), "html", null, true);
            echo "
            </div><!-- /.entry-thumbnail -->
        ";
        }
        // line 26
        echo "    </header><!-- .entry-header -->

    ";
        // line 29
        echo "    ";
        if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "is_search", array(), "method")) {
            // line 30
            echo "        <div class=\"entry-summary\">
            ";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "the_excerpt", array(), "method"), "html", null, true);
            echo "
        </div><!-- .entry-summary -->
    ";
        } else {
            // line 34
            echo "        <div class=\"entry-content\">
            ";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_the_content", array(), "method"), "html", null, true);
            echo "
            ";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_link_pages", array(), "method"), "html", null, true);
            echo "
        </div><!-- .entry-content -->
    ";
        }
        // line 39
        echo "</article><!-- /#post -->
";
    }

    public function getTemplateName()
    {
        return "content.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  111 => 39,  105 => 36,  101 => 35,  98 => 34,  92 => 31,  89 => 30,  86 => 29,  82 => 26,  76 => 23,  73 => 22,  70 => 21,  67 => 19,  59 => 16,  56 => 15,  50 => 13,  48 => 12,  45 => 11,  39 => 8,  35 => 7,  32 => 6,  30 => 5,  26 => 3,  19 => 1,);
    }
}
