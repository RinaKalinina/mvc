<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* User/index.twig */
class __TwigTemplate_b67d2062d029e24f8ea50cac3a6b65a51f90de5c726401b099aa343ddf69fd38 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        if (($context["user"] ?? null)) {
            // line 2
            echo "    Профиль пользователя: ";
            echo twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "getName", [], "method", false, false, false, 2);
            echo "
    <br>
    <br>
    <form action=\"/user/logout\" method=\"post\">
        <input type=\"submit\" value=\"Выйти\">
    </form>
    <br>
    <br>
    <form action=\"/user/feedback\" method=\"post\">
        Оставить отзыв: <br><br>
        <textarea name=\"message\" style=\"width: 250px; height: 100px;\"></textarea><br><br>
        <input type=\"submit\" name=\"submit\" value=\"Отправить\">
    </form>
    <br>
    <br>

    ";
            // line 18
            if ((isset($context["errors"]) || array_key_exists("errors", $context))) {
                // line 19
                echo "        ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["errors"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
                    // line 20
                    echo "            <span style=\"color: red\"> ";
                    echo (($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["errorDescription"] ?? null)) && is_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4) || $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 instanceof ArrayAccess ? ($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4[$context["error"]] ?? null) : null);
                    echo " </span>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 22
                echo "    ";
            }
        } else {
            // line 24
            echo "    Пользователь не найден
";
        }
        // line 26
        echo "
";
    }

    public function getTemplateName()
    {
        return "User/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  83 => 26,  79 => 24,  75 => 22,  66 => 20,  61 => 19,  59 => 18,  39 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% if user %}
    Профиль пользователя: {{ user.getName() }}
    <br>
    <br>
    <form action=\"/user/logout\" method=\"post\">
        <input type=\"submit\" value=\"Выйти\">
    </form>
    <br>
    <br>
    <form action=\"/user/feedback\" method=\"post\">
        Оставить отзыв: <br><br>
        <textarea name=\"message\" style=\"width: 250px; height: 100px;\"></textarea><br><br>
        <input type=\"submit\" name=\"submit\" value=\"Отправить\">
    </form>
    <br>
    <br>

    {% if errors is defined %}
        {% for error in errors %}
            <span style=\"color: red\"> {{ errorDescription[error] }} </span>
        {% endfor %}
    {% endif %}
{% else %}
    Пользователь не найден
{% endif %}

", "User/index.twig", "C:\\OpenServer\\domains\\mvc-new.lo\\app\\ViewTwig\\User\\index.twig");
    }
}
