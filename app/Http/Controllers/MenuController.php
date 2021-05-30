<?php


namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Routing\Controller as BaseController;
use DB;

class MenuController extends BaseController
{
    /*
    Requirements:
    - the eloquent expressions should result in EXACTLY one SQL query no matter the nesting level or the amount of menu items.
    - it should work for infinite level of depth (children of childrens children of childrens children, ...)
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - open the `app/Http/Controllers/MenuController` file
    - eager loading cannot load deeply nested relationships
    - a recursive function in php is needed to structure the query results
    - partial or not working answers also get graded so make sure you commit what you have


    Sample response on GET /menu:
    ```json
    [
        {
            "id": 1,
            "name": "All events",
            "url": "/events",
            "parent_id": null,
            "created_at": "2021-04-27T15:35:15.000000Z",
            "updated_at": "2021-04-27T15:35:15.000000Z",
            "children": [
                {
                    "id": 2,
                    "name": "Laracon",
                    "url": "/events/laracon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 3,
                            "name": "Illuminate your knowledge of the laravel code base",
                            "url": "/events/laracon/workshops/illuminate",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 4,
                            "name": "The new Eloquent - load more with less",
                            "url": "/events/laracon/workshops/eloquent",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                },
                {
                    "id": 5,
                    "name": "Reactcon",
                    "url": "/events/reactcon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 6,
                            "name": "#NoClass pure functional programming",
                            "url": "/events/reactcon/workshops/noclass",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 7,
                            "name": "Navigating the function jungle",
                            "url": "/events/reactcon/workshops/jungle",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                }
            ]
        }
    ]
     */

    public function getMenuItems() {
       $menuItemsData = DB::select('SELECT menu_items.id as menu_id,menu_items.name as menu_name,menu_items.url as menu_url,menu_items.parent_id as menu_parent_id,menu_items.created_at as menu_created_at,menu_items.updated_at as menu_updated_at,
                                           children_level1.id as children_level1_id,children_level1.name as children_level1_name,children_level1.url as children_level1_url,children_level1.parent_id as children_level1_parent_id,children_level1.created_at as children_level1_created_at,children_level1.updated_at as children_level1_updated_at,
                                           children_level2.id as children_level2_id,children_level2.name as children_level2_name,children_level2.url as children_level2_url,children_level2.parent_id as children_level2_parent_id,children_level2.created_at as children_level2_created_at,children_level2.updated_at as children_level2_updated_at
                                           FROM `menu_items`
                                    LEFT JOIN menu_items as children_level1 on children_level1.parent_id = menu_items.id
                                    LEFT JOIN menu_items as children_level2 on children_level2.parent_id = children_level1.id
                                    WHERE menu_items.parent_id is null');

        $resultData = array( "id" => "",
                             "name" => "",
                             "url" => "",
                             "parent_id" => "",
                             "created_at" => "",
                             "updated_at" => "",
                             "children" => array()
                            );

        foreach( $menuItemsData as $menuItemData ){;

            $childrenLevel2Data = array();

            if( $menuItemData->menu_id == $resultData["id"] ){
                if( isset( $resultData["children"][$menuItemData->children_level1_id] ) ){
                    $childrenLevel2Data["id"] = $menuItemData->children_level2_id;
                    $childrenLevel2Data["name"] = $menuItemData->children_level2_name;
                    $childrenLevel2Data["url"] = $menuItemData->children_level2_url;
                    $childrenLevel2Data["parent_id"] = $menuItemData->children_level2_parent_id;
                    $childrenLevel2Data["created_at"] = $menuItemData->children_level2_created_at;
                    $childrenLevel2Data["updated_at"] = $menuItemData->children_level2_updated_at;
                    $childrenLevel2Data["children"] = array();

                    $resultData["children"][$menuItemData->children_level1_id]["children"][] = $childrenLevel2Data;
                }
                else{

                    $resultData["children"][$menuItemData->children_level1_id]["id"] = $menuItemData->children_level1_id;
                    $resultData["children"][$menuItemData->children_level1_id]["name"] = $menuItemData->children_level1_name;
                    $resultData["children"][$menuItemData->children_level1_id]["url"] = $menuItemData->children_level1_url;
                    $resultData["children"][$menuItemData->children_level1_id]["parent_id"] = $menuItemData->children_level1_parent_id;
                    $resultData["children"][$menuItemData->children_level1_id]["created_at"] = $menuItemData->children_level1_created_at;
                    $resultData["children"][$menuItemData->children_level1_id]["updated_at"] = $menuItemData->children_level1_updated_at;

                    $childrenLevel2Data["id"] = $menuItemData->children_level2_id;
                    $childrenLevel2Data["name"] = $menuItemData->children_level2_name;
                    $childrenLevel2Data["url"] = $menuItemData->children_level2_url;
                    $childrenLevel2Data["parent_id"] = $menuItemData->children_level2_parent_id;
                    $childrenLevel2Data["created_at"] = $menuItemData->children_level2_created_at;
                    $childrenLevel2Data["updated_at"] = $menuItemData->children_level2_updated_at;
                    $childrenLevel2Data["children"] = array();

                    $resultData["children"][$menuItemData->children_level1_id]["children"][] = $childrenLevel2Data;
                }
            }
            else{
                $resultData["id"] = $menuItemData->menu_id;
                $resultData["name"] = $menuItemData->menu_name;
                $resultData["url"] = $menuItemData->menu_url;
                $resultData["parent_id"] = $menuItemData->menu_parent_id;
                $resultData["created_at"] = $menuItemData->menu_created_at;
                $resultData["updated_at"] = $menuItemData->menu_updated_at;

                $resultData["children"][$menuItemData->children_level1_id]["id"] = $menuItemData->children_level1_id;
                $resultData["children"][$menuItemData->children_level1_id]["name"] = $menuItemData->children_level1_name;
                $resultData["children"][$menuItemData->children_level1_id]["url"] = $menuItemData->children_level1_url;
                $resultData["children"][$menuItemData->children_level1_id]["parent_id"] = $menuItemData->children_level1_parent_id;
                $resultData["children"][$menuItemData->children_level1_id]["created_at"] = $menuItemData->children_level1_created_at;
                $resultData["children"][$menuItemData->children_level1_id]["updated_at"] = $menuItemData->children_level1_updated_at;

                $childrenLevel2Data["id"] = $menuItemData->children_level2_id;
                $childrenLevel2Data["name"] = $menuItemData->children_level2_name;
                $childrenLevel2Data["url"] = $menuItemData->children_level2_url;
                $childrenLevel2Data["parent_id"] = $menuItemData->children_level2_parent_id;
                $childrenLevel2Data["created_at"] = $menuItemData->children_level2_created_at;
                $childrenLevel2Data["updated_at"] = $menuItemData->children_level2_updated_at;
                $childrenLevel2Data["children"] = array();

                $resultData["children"][$menuItemData->children_level1_id]["children"][] = $childrenLevel2Data;

            }
        }

        return $resultData;
    }
}
