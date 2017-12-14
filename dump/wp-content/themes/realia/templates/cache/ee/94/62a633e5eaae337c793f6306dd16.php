<?php

/* properties/enquire.twig */
class __TwigTemplate_ee9462a633e5eaae337c793f6306dd16 extends Twig_Template
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
        echo (isset($context["before_widget"]) ? $context["before_widget"] : null);
        echo "

";
        // line 3
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 4
            echo "    ";
            echo (isset($context["before_title"]) ? $context["before_title"] : null);
            echo (isset($context["title"]) ? $context["title"] : null);
            echo (isset($context["after_title"]) ? $context["after_title"] : null);
            echo "
";
        }
        // line 6
        echo "
<div class=\"content\">
    <form method=\"post\" action=\"\">
        <input type=\"hidden\" name=\"form_id\" value=\"enquire-form\">

        <div class=\"control-group hidden\">
            <div class=\"controls\">
                <input type=\"text\" id=\"inputName\" name=\"full_name\">
            </div>
            <!-- /.controls -->
        </div><!-- /.control-group -->

        ";
        // line 18
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_name"), "method"))) {
            // line 19
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputName\">
                    ";
            // line 21
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Name", 1 => "aviators"), "method"), "html", null, true);
            echo "
                    <span class=\"form-required\" title=\"This field is required.\">*</span>
                </label>

                <div class=\"controls\">
                    <input type=\"text\" id=\"inputName\" name=\"name\" required=\"required\">
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 31
        echo "
        ";
        // line 32
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_phone"), "method"))) {
            // line 33
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputPhone\">
                    ";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Phone", 1 => "aviators"), "method"), "html", null, true);
            echo "
                    <span class=\"form-required\" title=\"This field is required.\">*</span>
                </label>

                <div class=\"controls\">
                    <input type=\"text\" id=\"inputPhone\" name=\"phone\" required=\"required\">
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 45
        echo "
        ";
        // line 46
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_date"), "method"))) {
            // line 47
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputDate\">
                    ";
            // line 49
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Date", 1 => "aviators"), "method"), "html", null, true);
            echo "
                    <span class=\"form-required\" title=\"This field is required.\">*</span>
                </label>

                <div class=\"controls\">
                    <input type=\"date\" id=\"inputDate\" name=\"date\" required=\"required\">
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 59
        echo "
        ";
        // line 60
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_date_from"), "method"))) {
            // line 61
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputDate\">
                    ";
            // line 63
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Date From", 1 => "aviators"), "method"), "html", null, true);
            echo "
                </label>

                <div class=\"controls\">
                    <input type=\"date\" id=\"inputDate\" name=\"date_from\">
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 72
        echo "
        ";
        // line 73
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_date_to"), "method"))) {
            // line 74
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputDate\">
                    ";
            // line 76
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Date To", 1 => "aviators"), "method"), "html", null, true);
            echo "
                </label>

                <div class=\"controls\">
                    <input type=\"date\" id=\"inputDate\" name=\"date_to\" >
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 85
        echo "
        ";
        // line 86
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_email"), "method"))) {
            // line 87
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputEmail\">
                    ";
            // line 89
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Email", 1 => "aviators"), "method"), "html", null, true);
            echo "
                    <span class=\"form-required\" title=\"This field is required.\">*</span>
                </label>

                <div class=\"controls\">
                    <input type=\"email\" id=\"inputEmail\" name=\"email\" required=\"required\">
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 99
        echo "
        ";
        // line 100
        if ((!$this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "aviators_settings_get_value", array(0 => "properties", 1 => "fields", 2 => "hide_message"), "method"))) {
            // line 101
            echo "            <div class=\"control-group\">
                <label class=\"control-label\" for=\"inputMessage\">
                    ";
            // line 103
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Message", 1 => "aviators"), "method"), "html", null, true);
            echo "
                    <span class=\"form-required\" title=\"This field is required.\">*</span>
                </label>

                <div class=\"controls\">
                    <textarea id=\"inputMessage\" name=\"message\" required=\"required\"></textarea>
                </div>
                <!-- /.controls -->
            </div><!-- /.control-group -->
        ";
        }
        // line 113
        echo "
        <div class=\"form-actions\">
            <input type=\"hidden\" name=\"post_id\" value=\"";
        // line 115
        echo twig_escape_filter($this->env, (isset($context["post_id"]) ? $context["post_id"] : null), "html", null, true);
        echo "\">
            <button class=\"btn btn-primary arrow-right\">";
        // line 116
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["wp"]) ? $context["wp"] : null), "__", array(0 => "Send", 1 => "aviators"), "method"), "html", null, true);
        echo "</button>
        </div>
        <!-- /.form-actions -->
    </form>
</div><!-- /.content -->

";
        // line 122
        echo (isset($context["after_widget"]) ? $context["after_widget"] : null);
    }

    public function getTemplateName()
    {
        return "properties/enquire.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  214 => 122,  205 => 116,  201 => 115,  197 => 113,  184 => 103,  180 => 101,  178 => 100,  175 => 99,  162 => 89,  158 => 87,  156 => 86,  153 => 85,  141 => 76,  137 => 74,  135 => 73,  132 => 72,  120 => 63,  116 => 61,  114 => 60,  111 => 59,  98 => 49,  94 => 47,  92 => 46,  89 => 45,  76 => 35,  72 => 33,  70 => 32,  67 => 31,  54 => 21,  50 => 19,  48 => 18,  34 => 6,  26 => 4,  24 => 3,  19 => 1,);
    }
}
