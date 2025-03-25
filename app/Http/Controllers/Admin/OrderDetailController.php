<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderDetail $orderDetail)
    {
        // Check status of order: không cho update detail nếu đơn đã đc xử lý
        $order = Order::find($orderDetail->order_id);

        if ($order->status == 1) {
            return redirect()->back()->with('errorMessage', __('Processed orders cannot be updated!'));
        }
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'price' => 'numeric|min:0'
        ]);

        $params = $request->only([
            'quantity', 'price'
        ]);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $orderDetail->fill($params);
        $orderDetail->save();

        //update order
        $total = 0;
        $order_list = OrderDetail::where('order_id',$orderDetail->order_id)->get();
        foreach ($order_list as $items) {
            $total += ($items->price * $items->quantity);
        }
        $total +=  (float)$order->shipping - (float)$order->discount;
        $order->total = $total;
        $order->save();





        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->id) {
            $orderDetail = OrderDetail::find($request->id);
            // Check status of order: không cho update detail nếu đơn đã đc xử lý
            $order = Order::find($orderDetail->order_id);
            if ($order->status == 1) {
                session()->flash('errorMessage', 'Processed orders cannot be removed!');
                return;
            }
            $orderDetail->delete();
            session()->flash('successMessage', 'Product removed successfully!');
            return;
        }
    }
}
