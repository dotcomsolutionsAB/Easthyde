
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<div class="kt-portlet kt-portlet--mobile">
       
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table role="grid" class="table table-striped table-scrollable table-scrollable-borderless dataTable" id="rop_datatable">
                <thead>
                    <tr>
                        <th> SN </th>
                        <th> Name </th>
                        <th> Group </th>
                        <th> Category </th>
                        <th> Sub-Category</th>
                        <th> Sale Price </th>
                        <th> HSN </th>
                        <th> Current Stock </th>
                        <th> ROP </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tfoot align="right">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

    <form class="kt-form kt-form--label-right" id="add_purchase_bag">
    <div class="modal fade" id="kt_modal_add_purchase_bag" tabindex="-1" role="dialog" aria-labelledby="addPurchaseBagModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPurchaseBagModal" >Add to Purchase Bag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input name="pb_product" id="pb_product" class="form-control" type="text">
                                </div>                                          
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                        <label>Quantity</label>
                                    <input name="pb_quantity" id="pb_quantity" class="form-control" type="text">
                                    <span class="form-text text-muted">Please enter the the quantity required .</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="add_pb_submit" type="submit" class="btn btn-primary">Submit </button>
                </div>
            </div>
        </div>
    </div>
</form>