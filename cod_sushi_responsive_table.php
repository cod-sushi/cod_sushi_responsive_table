<?php
/*
    Plugin Name: Responsive Table
    Plugin URI: 
    Description: Make tables responsive.
    Version: 0.0.1
    Author: cod
    Author URI: https://cod-sushi.com
    License: GPL2
*/

/*  Copyright 2019 cod (email : xxx@xxx.xxx)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
     published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Cod_Sushi_Responsive_Table {
    public function __construct() {
        // スタイルシートを読み込む処理をキューに追加する
        add_action('wp_enqueue_scripts', array( $this, 'add_style_sheet'));
		// コンテンツ読み込み時、テーブルをdivタグで囲む
        add_filter('the_content', array( $this, 'make_table_responsive'));
    }
    
    public function add_style_sheet() {
        wp_enqueue_style(
            'CodSushiResponsiveTableStyle',
            plugins_url('style/style.css', __FILE__)
        );
    }
    public function make_table_responsive( $content ) {
        if ( is_single() == false){
            return $content;
        }
        
        // HTMLからDOMDocumentを生成
        // UTF-8で読み込む
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">' . $content);
        
        // ラッパーdivを生成
        $new_div = $dom->createElement('div');
        $new_div->setAttribute('class','cod-table-wrapper');
        
        // すべてのテーブルを取得
        $tables = $dom->getElementsByTagName('table');
        foreach ($tables AS $table) {
            // ラッパーdivを複製し、テーブルノードと置き換える
            // 複製したdivに、テーブルを子要素として追加する
            $new_div_clone = $new_div->cloneNode();
            $table->parentNode->replaceChild($new_div_clone,$table);
            $new_div_clone->appendChild($table);
        }
        return $dom->saveXML($dom->doctype) .
               $dom->saveHTML($dom->documentElement);
     }
}

$CodSushiResponsiveTable = new Cod_Sushi_Responsive_Table();

?>