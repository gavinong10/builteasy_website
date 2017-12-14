<?php

/* comments.twig */
class __TwigTemplate_b8dadcaa9492f0dd2cac95cef6aae4ae extends Twig_Template
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
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "post_password_required", array(), "method"))) {
            // line 2
            echo "    <div id=\"comments\" class=\"comments-area\">
        ";
            // line 3
            if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "have_comments", array(), "method")) {
                // line 4
                echo "            <h2 class=\"comments-title\">";
                echo (isset($context["title"]) ? $context["title"] : null);
                echo "</h2>

            <ol class=\"comment-list\">
                ";
                // line 7
                echo twig_escape_filter($this->env, aviators_templates_helpers_wp_list_comments("array('style' => 'ol', 'format' => 'html5', 'short_ping' => 'true', 'avatar_size' => 74 )"), "html", null, true);
                echo "
            </ol>

            ";
                // line 10
                if ((($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_comment_pages_count", array(), "method") > 1) && $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_option", array(0 => "page_comments"), "method"))) {
                    // line 11
                    echo "                <nav class=\"navigation comment-navigation\" role=\"navigation\">
                    <h1 class=\"screen-reader-text section-heading\">";
                    // line 12
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Comment navigation", 1 => "aviators"), "method"), "html", null, true);
                    echo "</h1>
                    <div class=\"nav-previous\">";
                    // line 13
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "previous_comments_link", array(0 => $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "&larr; Older Comments", 1 => "aviators"), "method")), "method"), "html", null, true);
                    echo "</div>
                    <div class=\"nav-next\">";
                    // line 14
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "next_comments_link", array(0 => $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Newer Comments &rarr;", 1 => "aviators"), "method")), "method"), "html", null, true);
                    echo "</div>
                </nav><!-- .comment-navigation -->
            ";
                }
                // line 17
                echo "
            ";
                // line 18
                if (((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "comments_open", array(), "method")) && $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_comments_number", array(), "method"))) {
                    // line 19
                    echo "                <p class=\"no-comments\">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Comments are closed.", 1 => "aviators"), "method"), "html", null, true);
                    echo "</p>
            ";
                }
                // line 21
                echo "        ";
            }
            // line 22
            echo "
        ";
            // line 23
            echo twig_escape_filter($this->env, aviators_templates_helpers_comment_form("array('format' => 'html5')"), "html", null, true);
            echo "
    </div><!-- /#comments -->
";
        }
    }

    public function getTemplateName()
    {
        return "comments.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  75 => 23,  72 => 22,  69 => 21,  63 => 19,  61 => 18,  58 => 17,  52 => 14,  48 => 13,  44 => 12,  41 => 11,  39 => 10,  33 => 7,  26 => 4,  24 => 3,  21 => 2,  19 => 1,);
    }
}
