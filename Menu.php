<?php
namespace App\Controller\Menu;
use App\Views\View;

// Vale ressaltar que utilizamos bootstrap e Javascript para estilo e controle de nosso menu 

/**
 * Esse código renderiza um menu 100% dinâmico, atribuindo label, links, titulos e classes
 * você deve ter um javascript para controlar o abre e fecha do dropdown.
 * utilize ou javascript para abrir e fechar o dropdown
*/
class Menu
{
    /***/
    private static $modulos = [
        'home' => [
            'label'=>'Home', 
            'link' => '/'
        ],
        'sobre' => [
            'label'=>'Sobre nós', 
            'link' => '/sobre'
        ],
        'contato' => [
            'label'=>'Contato', 
            'link' => '/contato'
        ]
    ];

    /***/    
    private static $dropModulos = [
        'home' => [
            'label'=> 'home', 
            'link' => '/',
            'title'=> 'Home',
            'sub'   => []
        ],
        'profile'  => [
            'label'=> 'Profile',
            'title' => "Seu perfil", 
            'sub'   => [
                [
                    "label"=> "profile",
                    "title" => "Dados do perfil",
                    "link"=> '/profile'
                ],
                [
                    "label" => 'Acount',
                    "title" => "Acesse sua conta",
                    "link"  => "/acount"
                ]
            ]
        ],
        'sobre'    => [
            'label'=> 'sobre', 
            'link' => '/sobre',
            'sub'   => []
        ]        
    ];

    /** 
     * No Construtor do menu
     * Pode ser definidas algumas configurações e controle de exibição
     */
    public function __construct() {        
        $AddLabel = 'Mais';
        $AddLink  = '/ofertas';
        $AddTitle = 'Acesse suas ofertas';
        $AddSubmenu = [
            [
                'label' => 'Outras ofertas',
                'title' => 'Descubra outras ofertas imperdiveis',
                'link' => '/ofertas'
            ],
            [
                'label' => 'Promoção',
                'title' => 'Prompção do dia',
                'link' => '/promocao'
            ]
        ];

        // opcional
        if(logado()){ // Esse metodo você deve implementar, para verificar a sessão do usuário  
            if(!isset(self::$dropModulos['ofertas']) || (self::$dropModulos['ofertas']) == ''){
                self::addSubMenuToDropModulos($AddLabel, $AddLink, $AddSubmenu, $AddTitle);
            }
        }        
    }

    /**
     * Método responsável por renderizar o menu o verificar sa há existencia de sub-menu
     *
     * @param string $currentModel
     * @param array $menuItems
     * @param string $btn
     *
     * @return string
     */
    public static function renderMenu($currentModel, $menuItems, $btn=null) {
        // o parametro opcional `$btn` pode ser uma outra view renderizada, podendo ser
        // por exemplo botões de login/logout
        
        $dropdownItems = '';
        $currentLevel = $_SERVER['REQUEST_URI'];

        foreach($menuItems as $model => $item) {
            // Verificar o link corrente
            $isActive = ( $currentModel === $model) ? 'active' : '';

            $hasDropdown = (!empty($item['sub']));
            $title = (isset($item['title']) && !is_null($item['title'])) ? $item['title'] : '';

            if ($hasDropdown) {
                $dropdownItems .= self::renderDropdownItem($item['label'], $item['sub'], $isActive, $title);
            } else {
                $dropdownItems .= self::renderLinkItem($item['label'], $item['link'], $isActive, $title);
            }
        }
        //$btn = View::render('');

        return View::render('res/menu/header', [
            'menu' => self::renderDropdown($dropdownItems),
            "btn" => $btn
        ]);
    }

    /**
     * Description
     *
     * @param string $label
     * @param string $link
     * @param string $isActive
     *
     * @return string
     */
    private static function renderLinkItem($label, $link, $isActive, $title=null) {
        $title = (isset($title) && !is_null($title)) ? $title : '';

        return View::render('res/menu/sub/subitem', [
            "activeClass" => $isActive,
            "link" => $link,
            "title"=> !is_null($title) ? $title : '',
            "label"=> ucfirst($label)
        ]);
    }

    /**
     * Renderiza o sub-menu se houver níveis de sub-menu
     *
     * @param string $label
     * @param string $submenu
     * @param string $isActive
     * 
     * @return string
     */
    private static function renderDropdownItem($label, $submenu, $isActive) {
        $dropdownItems = '';

        $currentSubMenu = $_SERVER['REQUEST_URI'];
        $activeClass = $isActive ? 'active' : '';

        foreach ($submenu as $item) {
            $ActiveSub = '';
            //if (Config::ImAdmin()) {
            //    new Debug($item);
            //}

            $title = !is_null($item['title']) ? $item['title'] : '';

            $ActiveSub = ($currentSubMenu === $item['link']) ? 'active-sub' : ''; // Aplique a classe "active-sub" apenas ao link ativo do submenu

            $dropdownItems .= self::renderLinkItem($item['label'], $item['link'], $ActiveSub, $title);
        }



        return View::render('res/menu/sub/submenu', [
            "activeClass" => $activeClass, // Adicione a classe "active" se for o link ativo do menu principal
            "title" => !is_null($title) ? $title : '',
            "label" => ucfirst($label),
            "dropdownItems" => $dropdownItems,
        ]);
    }

    /**
     * Método responsável por retornar o menu renderizado já com os links
     *
     * @param string $dropdownItens
     * @return mixed 
     */
    private static function renderDropdown($dropdownItems) {
        return View::render('res/menu/dropdown',[
            "dropdownItems" => $dropdownItems
        ]);
    }

    // Metodo dinamico para adicionar links no menu
    public static function addSubMenuToDropModulos($label, $link, $submenu, $title=null) {
        self::$dropModulos[$label] = [
            'label'   => ucfirst($label),
            'link'    => $link,
            'submenu' => $submenu,
            "title"   => !is_null($title) ? $title : ''
        ];
    }

    /**
     * @return mixed
     */
    public function getDropModulos() {
        return self::$dropModulos;
    }

    /**
     * @param mixed $dropModulos
     *
     * @return self
     */
    public function setDropModulos($dropModulos) {
        self::$dropModulos = $dropModulos;

        return self::$dropModulos;
    }

    /**
     * @return mixed
     */
    public function getModulos() {
        return self::$modulos;
    }

    /**
     * @param mixed $modulos
     *
     * @return self
     */
    public function setModulos($modulos) {
        self::$modulos = $modulos;

        return self::$modulos;
    }
}
