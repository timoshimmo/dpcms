<!-- modal starts here -->
<!-- The Modal -->
<div class="modal" id="myModalViewFundWalletPaymentProof" class="modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Payment Proof Wallet Funding</h4>
        <button type="button" class="close btn-modal-closeout" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
         <input type="hidden" class="fw_input_userid" value="">
                    <input type="hidden" class="fw_input_user_acctid" value="">
                    <input type="hidden" class="fw_input_paymentproof_id" value="">
        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                 <div class="form-group">
                        <label class="control-label">Member's Name</label>
                        <div>
                            <input type="text" class="form-control fw_input_membername" readonly>
                        </div>
                    </div>
            </div>
        </div>



        <div class="row" style="margin-top: 17px;">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                 <div class="form-group">
                        <label class="control-label">Account to Fund</label>
                        <div>
                            <input type="text" class="form-control input-lg" value="PRIMARY ACCOUNT" readonly disabled>
                        </div>
                    </div>
            </div>
        </div>

        <div class="row" style="margin-top: 17px;">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                        <label class="control-label">Amount to Fund</label>
                        <div>
                            <input type="text" class="form-control input-lg fw_input_amounttofund" placeholder="Enter an Amount to fund the user's wallet">
                        </div>
                </div>
            </div>
        </div>


        <div class="row" style="margin-top: 17px;">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg btn-modal-fund-wallet">Fund Wallet</button>
                        </div>
                    </div>
            </div>
        </div>
                   

                   


                    

      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-modal-closeout" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- modal ends here -->