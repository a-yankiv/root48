  <?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Shop extends Controller_Common {
 
 
    public function action_index() {
        $counter = ORM::factory('shop');
        $countRecords = $counter->count_all();
        $pagination = Pagination::factory(array('total_items' => $countRecords))
                ->route_params(array(
                        'controller' => Request::current()->controller(),
                        'action' => Request::current()->action(),
            ));
        //$pagination = Pagination::factory(array('total_items' => $countRecords));
        $shop2 = ORM::factory('Shop')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();
        $content = View::factory('/shop/shop')
                ->bind('shop', $shop2)
                ->bind('imgarr', $imgarr)
                ->bind('imagedata', $imagedata)
                ->bind('pagination',  $pagination);
        
        
        $imgpath = URL::base('/public/uploads/'); 
        $shop = ORM::factory('Shop')
                ->find_all()
                ->as_array();  
        foreach ($shop as $key) 
        {
         $imagedata = $key->images->find_all();
            foreach ($imagedata as $imvalue) 
                {
                  $imgarr[$key->id] = $imvalue->name;
                }   
            if ((strlen($key->short_desc)) > 15 )
            {
                $slicedstr = Text::limit_words($key->short_desc, 10);
                $lenedit = ORM::factory('Shop', $key->id)
                        ->set('short_desc', $slicedstr)
                        ->save();
            }    
//          #var_dump($imgarr);
//         //print_r($imgarr);
        }

//        foreach ($shop as $imgvalue) {
//            //костыли-костылёчки
//            
//            $imagemodel = ORM::factory('Image')
//                    ->where('shops_id', '=', $imgvalue->id)
//                    ->find_all();
//            $imgarr = array();
//            foreach ($imagemodel as $ivalue) 
//            {
//                $imgarr = $ivalue->name;
//            }
//            
//        }
        $this->template->content = $content;
    }

    public function action_order() {
        $id = $this->request->param('id');
        $alturl = $this->request->param('alturl');
        if ($id) {
            $content = View::factory('/shop/order')
                    ->bind('goods', $goods);
            $goods = ORM::factory('Shop')
                    ->where('id', '=', $id)
                    ->and_where('alt_url', '=', $alturl)
                    ->find_all()
                    ->as_array();
//            $goods = $goods->get_one($id,$alturl);
            $this->template->content = $content;
        } else {
            $content = '';
            $this->template->content = $content;
        }
    }
    
    

} // Articles