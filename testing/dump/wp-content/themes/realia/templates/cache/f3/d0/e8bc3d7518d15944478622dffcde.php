<?php

/* agents/widget.twig */
class __TwigTemplate_f3d0e8bc3d7518d15944478622dffcde extends Twig_Template
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
        if ((isset($context["agents"]) ? $context["agents"] : null)) {
            // line 2
            echo "    ";
            echo (isset($context["before_widget"]) ? $context["before_widget"] : null);
            echo "

    ";
            // line 4
            if ((isset($context["title"]) ? $context["title"] : null)) {
                // line 5
                echo "        ";
                echo (isset($context["before_title"]) ? $context["before_title"] : null);
                echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
                echo (isset($context["after_title"]) ? $context["after_title"] : null);
                echo "
    ";
            }
            // line 7
            echo "
    <div class=\"content\">
        ";
            // line 9
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["agents"]) ? $context["agents"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["agent"]) {
                // line 10
                echo "            <div class=\"agent clearfix\">
                <div class=\"image\">
                    <a href=\"";
                // line 12
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_permalink", array(0 => $this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "ID")), "method"), "html", null, true);
                echo "\">
                        ";
                // line 13
                if ($this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_the_post_thumbnail", array(0 => $this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "ID")), "method")) {
                    // line 14
                    echo "                            ";
                    echo $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_the_post_thumbnail", array(0 => $this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "ID")), "method");
                    echo "
                        ";
                } else {
                    // line 16
                    echo "                            <img src=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_template_directory_uri", array(), "method"), "html", null, true);
                    echo "/assets/img/agent-tmp.png\" alt=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "post_title"), "html", null, true);
                    echo "\">
                        ";
                }
                // line 18
                echo "                    </a>
                </div>
                <!-- /.image -->

                <div class=\"name\">
                    <a href=\"";
                // line 23
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "get_permalink", array(0 => $this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "ID")), "method"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "post_title"), "html", null, true);
                echo "</a>
                </div>
                <!-- /.name -->
                <div class=\"phone\">";
                // line 26
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "meta"), "_agent_mobile"), 0), "html", null, true);
                echo "</div>
                <!-- /.phone -->
                <div class=\"email\"><a href=\"mailto:";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "meta"), "_agent_email"), 0), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["agent"]) ? $context["agent"] : null), "meta"), "_agent_email"), 0), "html", null, true);
                echo "</a>
                </div>
                <!-- /.email -->
            </div><!-- /.agent -->
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['agent'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 33
            echo "    </div><!-- /.content -->

    ";
            // line 35
            echo (isset($context["after_widget"]) ? $context["after_widget"] : null);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "agents/widget.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  106 => 35,  102 => 33,  89 => 28,  84 => 26,  76 => 23,  69 => 18,  61 => 16,  55 => 14,  53 => 13,  49 => 12,  45 => 10,  41 => 9,  37 => 7,  29 => 5,  27 => 4,  21 => 2,  19 => 1,);
    }
}
