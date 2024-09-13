<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Exception;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function addItems(Request $request)
    {
        $item = $request ->validate([
            'item_name'=>'required',
            'description' =>'required',
            'quantity'=> 'required',
            'price'=> 'required'

        ]);
        try{
            $items= new Item();
            $items->item_name=$request->item_name;
            $items->description=$request->description;
            $items->quantity=$request->quantity;
            $items->price= $request->price;
            $items->save();
            return redirect()->back()->with('message','item Added Successfully..');
           
        }catch (Exception $e) {

            return 'Exception: ' . $e->getMessage(). 
                   ' in file ' . $e->getFile() . 
                   ' on line ' . $e->getLine();
        }
    }
    public function showItems(){
        
        $data = Item::all();
        return view ('home' , compact('data'));
    } 
    public function deleteItem($id)
    {
          $item = Item::find($id);
          if ($item) {
          $item->delete();
              return redirect()->back()->with('message', 'Item deleted successfully.');
          } else {
              return redirect()->back()->with('error', 'Item not found.');
              
            }
            
            
    }
    public function editItem($id){
        $item = Item::find($id); 

        if ($item) {
        $item->update();
            return view('items.edit', compact('item')); 
        } else {
            return redirect()->back()->with('error', 'Item not found.');
           
        }
        
    }

    public function update(Request $request, $id){

    $item = Item::find($id);

    if (!$item) {
        return redirect()->back()->with('error', 'Item not found');
    }

    $request->validate([
        'item_name' => 'required|string|max:255',
        'description' => 'required|string',
        'quantity' => 'required|integer',
        'price' => 'required|numeric',
    ]);


        $item->item_name = $request->item_name;
        $item->description = $request->description;
        $item->quantity = $request->quantity;
        $item->price = $request->price;
        $item->save();

         return redirect()->back()->with('success', 'Item updated successfully');
    } 
}






