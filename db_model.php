<?php

class db_model
{
    private $tableProducts;

    public function __construct()
    {
        $this->tableProducts = "products";
    }

    function connect($dbHost, $dbUser, $dbPassword, $dbName)
    {
        $con = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        if ($con->connect_errno) {

            printf("connection failed: %s\n", $con->connect_error());
            return false;
        }
        return $con;
    }

    function prepareTables($con)
    {
        $query = "CREATE TABLE IF NOT EXISTS products (
        prod_name VARCHAR(1024),
        prod_id INT NOT NULL,
        prod_price FLOAT(8,2),
        prod_tax_id FLOAT(8,2),
        taxpercent INT,
        prod_oldprice FLOAT(8,2),
        prod_buy_price_net FLOAT(8,2),
        prod_amount FLOAT(10,4),
        prod_hidden INT,
        prod_symbol VARCHAR(255),
        prod_weight INT,
        prd_name VARCHAR(255),
        prod_pkwiu VARCHAR(255),
        prod_ean VARCHAR(15),
        prod_isbn VARCHAR(15),
        prod_barcode VARCHAR(15),
        prod_ship_days INT,
        prod_desc LONGTEXT,
        prod_shortdesc MEDIUMTEXT,
        prod_info1_pl MEDIUMTEXT,
        prod_info2_pl MEDIUMTEXT,
        prod_info3_pl MEDIUMTEXT,
        prod_info4_pl MEDIUMTEXT,
        prod_info5_pl MEDIUMTEXT,
        prod_note VARCHAR(255),
        prod_seotitle_pl VARCHAR(255),
        prod_seodesc_pl VARCHAR(255),
        prod_keywords_pl VARCHAR(255),
        prod_sales_gain VARCHAR(255),
        prod_link VARCHAR(255),
        prod_price_base FLOAT(8,2),
        prod_price_net_base FLOAT(8,2),
        prod_price_net FLOAT(8,2),
        cat_path VARCHAR(255),
        PRIMARY KEY (prod_id)
        )";
        $this->execute_query($query, $con);

        $query = "CREATE TABLE IF NOT EXISTS products_options (
        option_id INT NOT NULL AUTO_INCREMENT,
        prod_id INT NOT NULL,
        option_name VARCHAR(255),
        option_value VARCHAR(255),
        PRIMARY KEY (option_id)
        )";
        $this->execute_query($query, $con);

        $query = "CREATE TABLE IF NOT EXISTS products_images (
        image_id INT NOT NULL AUTO_INCREMENT,
        prod_id INT NOT NULL,
        image_url VARCHAR(255),
        PRIMARY KEY (image_id)
        )";
        $this->execute_query($query, $con);
        return true;
    }

    function execute_query($query, $con)
    {
        $res = $con->query($query);

        if (!$res) {

            echo "failed to execute query: $query\n";
        }

        if (is_object($res)) {

            $res->close();
        }
    }

    function insert_product($con, $item)
    {
        $this->insert_product_options($con, $item);
        $this->insert_product_images($con, $item);
        $this->insert_product_info($con, $item);
    }

    function insert_product_info($con, $item)
    {
        foreach ($item as $key => $field) {
            if (is_array($field)) {
                $item[$key] = NULL;
            }
        }
        $prod_name = strval($item['prod_name']);
        $prod_id = (int)$item['prod_id'];
        $prod_price = floatval($item['prod_price']);
        $prod_tax_id = floatval($item['prod_tax_id']);
        $taxpercent = (int)$item['taxpercent'];
        $prod_old_price = floatval($item['prod_oldprice']);
        $prod_buy_price_net = floatval($item['prod_buy_price_net']);
        $prod_amount = floatval($item['prod_amount']);
        $prod_hidden = (int)$item['prod_hidden'];
        $prod_symbol = strval($item['prod_symbol']);
        $prod_weight = (int)$item['prod_weight'];
        $prd_name = strval($item['prd_name']);
        $prod_pkwiu = strval($item['prod_pkwiu']);
        $prod_ean = strval($item['prod_ean']);
        $prod_isbn = strval($item['prod_isbn']);
        $prod_barcode = strval($item['prod_barcode']);
        $prod_ship_days = (int)$item['prod_ship_days'];
        $prod_desc = strval($item['prod_desc']);
        $prod_shortdesc = strval($item['prod_shortdesc']);
        $prod_info1_pl = strval($item['prod_info1_pl']);
        $prod_info2_pl = strval($item['prod_info2_pl']);
        $prod_info3_pl = strval($item['prod_info3_pl']);
        $prod_info4_pl = strval($item['prod_info4_pl']);
        $prod_info5_pl = strval($item['prod_info5_pl']);
        $prod_note = strval($item['prod_note']);
        $prod_seotitle_pl = strval($item['prod_seotitle_pl']);
        $prod_seodesc_pl = strval($item['prod_seodesc_pl']);
        $prod_keywords_pl = strval($item['prod_keywords_pl']);
        $prod_sales_gain = strval($item['prod_sales_gain']);
        $prod_link = strval($item['prod_link']);
        $prod_price_base = floatval($item['prod_price_base']);
        $prod_price_net_base = floatval($item['prod_price_net_base']);
        $prod_price_net = floatval($item['prod_price_net']);
        $cat_path = strval($item['cat_path']);
        $query = $con->prepare("INSERT INTO products
                  VALUES (
                  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                  )");
        $query->bind_param(
            'siddidddisisssssisssssssssssssddds',
            $prod_name,
            $prod_id,
            $prod_price,
            $prod_tax_id,
            $taxpercent,
            $prod_old_price,
            $prod_buy_price_net,
            $prod_amount,
            $prod_hidden,
            $prod_symbol,
            $prod_weight,
            $prd_name,
            $prod_pkwiu,
            $prod_ean,
            $prod_isbn,
            $prod_barcode,
            $prod_ship_days,
            $prod_desc,
            $prod_shortdesc,
            $prod_info1_pl,
            $prod_info2_pl,
            $prod_info3_pl,
            $prod_info4_pl,
            $prod_info5_pl,
            $prod_note,
            $prod_seotitle_pl,
            $prod_seodesc_pl,
            $prod_keywords_pl,
            $prod_sales_gain,
            $prod_link,
            $prod_price_base,
            $prod_price_net_base,
            $prod_price_net,
            $cat_path
        );
        $query->execute();
    }

    function insert_product_options($con, $item)
    {
        $prod_id = (int)$item['prod_id'];
        if (array_key_exists('options', $item)) {
            foreach ($item['options'] as $option) {
                $query = $con->prepare("INSERT INTO products_options
                  (prod_id, option_name, option_value)
                  VALUES (
                  ?, ?, ?
                  )");
                $query->bind_param(
                    'iss',
                    $prod_id,
                    $option['option_attr']['name'],
                    $option['option']
                );
                $query->execute();
            }
        }
        if (array_key_exists('info_options', $item)) {

            $query = $con->prepare("INSERT INTO products_options
                  (prod_id, option_name, option_value)
                  VALUES (
                  ?, ?, ?
                  )");
            $query->bind_param(
                'iss',
                $prod_id,
                $item['info_options']['option_attr']['name'],
                $item['info_options']['option']
            );
            $query->execute();

        }
    }

    function insert_product_images($con, $item)
    {
        $prod_id = (int)$item['prod_id'];
        if (array_key_exists('prod_img', $item) && array_key_exists('img', $item['prod_img'])) {
            if (is_array($item['prod_img']['img'])) {
                foreach ($item['prod_img']['img'] as $image) {
                    $query = $con->prepare("INSERT INTO products_images
                  (prod_id, image_url)
                  VALUES (
                  ?, ?
                  )");
                    $query->bind_param(
                        'is',
                        $prod_id,
                        $image
                    );
                    $query->execute();
                }
            } else {
                $query = $con->prepare("INSERT INTO products_images
                  (prod_id, image_url)
                  VALUES (
                  ?, ?
                  )");
                $query->bind_param(
                    'is',
                    $prod_id,
                    $item['prod_img']['img']
                );
                $query->execute();
            }
        }
    }

    function Xml2Array($contents, $get_attributes = 1, $priority = 'tag')
    {
        if (!$contents) return array();

        if (!function_exists('xml_parser_create')) {
            return array();
        }

        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) return;

        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array;

        $repeated_tag_index = array();
        foreach ($xml_values as $data) {
            unset($attributes, $value);

            extract($data);

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag') $result = $value;
                else $result['value'] = $value;
            }

            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val;
                }
            }

            if ($type == "open") {
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data) $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];

                } else {

                    if (isset($current[$tag][0])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) $current[$tag . '_attr'] = $attributes_data;


                } else {
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {

                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;

                    } else {
                        $current[$tag] = array($current[$tag], $result);
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) {

                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                }

            } elseif ($type == 'close') {
                $current = &$parent[$level - 1];
            }
        }

        return ($xml_array);
    }

}

?>