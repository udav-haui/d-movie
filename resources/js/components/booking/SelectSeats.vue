<template>
    <div class="row" v-if="film.id != undefined">

        <div class="col-lg-11 col-md-11 col-sm-16 col-xs-16 margin-bottom-35 panelShowSeat">
            <div class="margin-bottom-35">
                <h3>
                    <a :href="homeRoute">Home</a> &gt;
                    <a href="#">Booking</a> &gt;
                    <span class="color1">
                        <a :href="film.filmUrl">{{ film.title }}</a>
                    </span>
                </h3>
            </div>

            <div v-if="decodeMarkAlert.ageMark != 'p'" class="blink padding-10" style="text-align: center; color: red; margin-bottom: 10px; background-color: rgb(243, 230, 192);">
                {{ decodeMarkAlert.alert }}
            </div>

            <div class="choose-seat-row" v-if="!isPreparePayment">
                <div id="screen_form">
                    <div class="note-seat-status">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
                            <img width="35" height="35" :src="baseUrl + '/Assets/global/img/booking/seat-unselect-normal.png'">
                            <span class="note-seat-status-label">Ghế trống</span>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
                            <img width="35" height="35" :src="baseUrl + '/Assets/global/img/booking/seat-select-normal.png'">
                            <span class="note-seat-status-label">Ghế đang chọn</span>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
                            <img width="35" height="35" :src="baseUrl + '/Assets/global/img/booking/seat-process-normal.png'">
                            <span class="note-seat-status-label">Ghế đang giữ</span>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
                            <img width="35" height="35" :src="baseUrl + '/Assets/global/img/booking/seat-buy-normal.png'">
                            <span class="note-seat-status-label">Ghế đã bán</span>
                        </div>
                    </div>


                    <div class="col-lg-16 col-xs-16 margin-top-20">
                        <div class="testimonial-group">
                            <div class="row">
                                <div class="seat-diagram pull-left">
                                    <img class="img-responsive" :src="baseUrl + '/Assets/global/img/booking/ic-screen.png'">
                                    <div class="check-show">
                                        <div class="full-width" v-for="row in decodeSeats">
                                            <div class="seat-cell seat-used"
                                                 :class="getSelectClass(decodeSeat)"
                                                 v-for="decodeSeat in row"
                                                 @click="selectSeat(decodeSeat)">
                                                {{ decodeSeat.type == 0 || decodeSeat.type == 1 ? decodeSeat.row + decodeSeat.number : decodeSeat.row + decodeSeat.number + '.2 - ' + decodeSeat.row + decodeSeat.number + '.1' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="seat-type-panel pull-left">
                    <div class="seat-type seat-type-standard col-lg-3 col-md-3 col-sm-16 col-xs-16">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-5">
                                <img class="seat-type-image" style="width: 100%; max-width: 50px;" :src="baseUrl+'/Assets/global/img/booking/seat-unselect-normal.png'">
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-11">
                                <span class="seat-type-name">{{ __('Normal seat') }}</span>
                            </div>
                            <div class="col-lg-16 col-md-16 col-sm-16 col-xs-16">
                                <span class="seat-empty-quantity seat-normal-quantity" v-if="selectedNormal.length > 0">{{selectedNormalLabel}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="seat-type seat-type-vip col-lg-3 col-md-3 col-sm-16 col-xs-16">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-5">
                                <img class="seat-type-image" style="width: 100%; max-width: 50px;" :src="baseUrl + '/Assets/global/img/booking/seat-unselect-vip.png'">
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-11">
                                <span class="seat-type-name">{{ __('VIP seat') }}</span>
                            </div>
                            <div class="col-lg-16 col-md-16 col-sm-16 col-xs-16" v-if="selectedVIP.length > 0">
                                <span class="seat-vip-quantity">{{selectedVIPLabel}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="seat-type seat-type-double col-lg-3 col-md-3 col-sm-16 col-xs-16">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-5">
                                <img class="seat-type-image" style="width: 100%; max-width: 50px;" :src="baseUrl + '/Assets/global/img/booking/seat-unselect-double.png'">
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-11">
                                <span class="seat-type-name">{{ __('Double seat') }}</span>
                            </div>
                            <div class="col-lg-16 col-md-16 col-sm-16 col-xs-16" v-if="selectedDouble.length > 0">
                                <span class="seat-double-quantity">{{selectedDoubleLabel}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="seat-type total-money total-sm-money total-xs-money col-lg-3 col-md-3 col-sm-16 col-xs-16 padding-sm-top-15 padding-xs-top-15 padding-lg-top-0">
                        <div class="row">
                            <div class="total-money-label col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                {{ __('Total amount') }}
                            </div>
                            <div class="total-money-value col-lg-16 col-md-16 col-sm-8 col-xs-8">{{totalAmountLabel}}
                            </div>
                        </div>
                    </div>
                    <div class="seat-type time-left time-sm-left time-xs-left col-lg-4 col-md-4 col-sm-16 col-xs-16 padding-sm-top-15 padding-xs-top-15 padding-lg-top-0">
                        <div class="row">
                            <div class="time-to-left-label col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                {{ __('Time remaining') }}
                            </div>
                            <div class="time-to-left-value col-lg-16 col-md-16 col-sm-8 col-xs-8 time-et">10:00</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="payment-seat-row" v-if="isPreparePayment">
                <div id="payment-form">
                    <div class="payment-page-title" style="height: 35px; line-height: 35px;">
                        <img style="height: 100%; float: left;" :src="baseUrl + '/Assets/global/img/booking/ic-inforpayment.png'">
                        <div class="page-title" id="scroll-top">{{ __('Payment information') }}</div>
                    </div>

                    <div class="payment-user-info font-family-san font-lg" style="margin-top: 25px; width: 100%; margin-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-5 user-info-item font-16">
                                <span class="bold user-info-item-label">{{ __('Name') }}: </span><br>
                                <span class="user-info-item-value">{{authUser.name}} </span>
                            </div>
                            <div class="col-md-5 user-info-item font-16">
                                <span class="bold user-info-item-label">{{ __('Phone') }}: </span><br>
                                <span class="user-info-item-value">{{authUser.phone}} </span>
                            </div>
                            <div class="col-md-5 user-info-item font-16">
                                <span class="bold user-info-item-label">{{ __('Email') }}: </span><br>
                                <span class="user-info-item-value">{{authUser.email}} </span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <br>

                    <div class="ticket-selected ticket-selected-quantity-detail" style="">
                        <div v-if="selectedNormal.length > 0">
                            <div class="row">
                                <div class="col-md-8 item-seat-type">{{ __('Normal seat')}}</div>
                                <div class="col-md-5 item-seat-quantity">{{selectedNormal.length}} x 45.000</div>
                                <div class="col-md-3 item-seat-money">= {{selectedNormal.length * 45000}}</div>
                            </div>
                            <div class="clearfix"></div>
                            <hr style="margin-top: 15px;">
                        </div>
                        <div v-if="selectedVIP.length > 0">
                            <div class="row">
                                <div class="col-md-8 item-seat-type">{{ __('VIP seat')}}</div>
                                <div class="col-md-5 item-seat-quantity">{{selectedVIP.length}} x 55.000</div>
                                <div class="col-md-3 item-seat-money">= {{selectedVIP.length * 55000}}</div>
                            </div>
                            <div class="clearfix"></div>
                            <hr style="margin-top: 15px;">
                        </div>
                        <div v-if="selectedDouble.length > 0">
                            <div class="row">
                                <div class="col-md-8 item-seat-type">{{ __('Double seat')}}</div>
                                <div class="col-md-5 item-seat-quantity">{{selectedDouble.length}} x 90.000</div>
                                <div class="col-md-3 item-seat-money">= {{ selectedDouble.length * 90000 }} vnđ</div>
                            </div>
                            <div class="clearfix"></div>
                            <hr style="margin-top: 15px;">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <br>
                    <div class="payment-page-title margin-bottom-20" style="height: 35px; line-height: 35px;">
                        <img style="height: 100%; float: left;" :src="baseUrl + '/Assets/global/img/booking/ic-combo.png'">
                        <div class="page-title">{{ __('Combo preferences')}}</div>
                    </div>
                    <table class="table table-striped table-hover table-combo-list">
                        <thead>
                        <tr>
                            <th class="text-center no-padding" style="width: 30%">
                                <h4>{{ __('Combo name')}}</h4>
                            </th>
                            <th class="text-center td-bg-1 no-padding" style="width: 40%">
                                <h4>{{ __('Description')}}</h4>
                            </th>

                            <th class="text-center td-bg-1 no-padding" style="width: 15%">
                                <h4>{{ __('Quantity')}}</h4>
                            </th>
                        </tr>
                        </thead>
                        <tbody v-if="decodeCombos.length > 0">
                        <tr class="item-outer-pannel" v-for="combo in decodeCombos">
                            <td class="combo-title">{{combo.name}}</td>
                            <td class="combo-item-des td-bg-1">
                                {{combo.description}}
                            </td>
                            <td class="combo-action td-bg-1">
                                <span style="float: right;margin-right: 5px;"
                                      @click="selectCombo($event,'unselect', combo)"
                                      class="btn-minus">

                                </span>
                                <span
                                    style="float: right;margin-right: 5px;"
                                    @click="selectCombo($event, 'select', combo)"
                                    class="btn-plus">

                                </span>
                                <span class="combo-quantity combo-quantity-0"
                                      style="float: right;margin-right: 15px;">{{combo.id === selectedCombo.id ? 1 : 0}}</span>
                            </td>
                        </tr>
                        </tbody>

                    </table>


                    <hr>
                    <div class="ticket-selected">
                        <div class="row">
                            <div class="col-md-8 item-seat-type"></div>
                            <div class="col-md-5 item-seat-quantity">{{__('Total')}}: </div>
                            <div class="col-md-3 item-seat-money item-seat-total-money total-money-name">{{totalAmountLabel}}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-13 item-seat-quantity">
                            {{__('The amount is reduced')}}:
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-16 col-xs-16">
                            <div class="coupon-discount text-right font-family-san" style="color: red;font-size: 1.5em;">0 vnđ</div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-13 item-seat-quantity">
                            {{ __('The payment amount')}}:
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-16 col-xs-16">
                            <div class="payment-amount text-right font-family-san" style="color: red; font-size: 1.5em;">{{totalAmountLabel}}</div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="payment-page-title" style="height: 35px; line-height: 35px;">
                        <img style="height: 100%; float: left;" :src="baseUrl + '/Assets/global/img/booking/ic-payment.png'">
                        <div class="page-title">{{ __('Payment method')}}</div>
                        <a class="pull-right" target="_blank" href="http://202.9.84.88/documents/payment/guideVN.jsp?logos=v,m,a,j,u,at">Payment guide</a>
                    </div>

                    <div class="tab-35">
                        <div class="option-title">{{__('Select payment method')}}</div>
                        <div class="col-md-8">
                            <input type="radio" id="card1" name="radio-group-card" @change="setPaymentMethod('momo')" value="momo" :checked="paymentMethod === 'momo'">
                            <label for="card1" style="display: flex; align-items: center">
                                <img style="width: 45px; float: left; margin-left: 5px; margin-right: 10px;" :src="baseUrl + '/Assets/global/img/booking/momo.png'">
                                <span style="line-height: 35px;">MoMo</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="radio" id="card2" name="radio-group-card" @change="setPaymentMethod('vnpay')" value="vnpay" :checked="paymentMethod === 'vnpay'">
                            <label for="card2" style="display: flex; align-items: center">
                                <img style="width: 45px; float: left; margin-left: 5px; margin-right: 10px;" :src="baseUrl + '/Assets/global/img/booking/vnpay.png'">
                                <span style="line-height: 35px;">VNPAY</span></label>
                        </div>
                    </div>
                </div>
                <div class="seat-type-panel">
                    <div class="seat-type seat-type-standard col-md-13 font-family-san">
                        <div class="note-before-next">{{ __('Please check the full information before the next step.')}}</div>
                        <div class="note-refund"><span style="color: red;">*</span>{{__('Tickets already purchased are not refundable in any form.')}}</div>
                    </div>

                    <div class="seat-type time-left col-md-3 padding-sm-top-15 padding-xs-top-15 padding-lg-top-0">
                        <div class="time-to-left-label">
                            {{ __('Time remaining') }}
                        </div>
                        <div class="time-to-left-value time-et">10:00</div>
                    </div>
                </div>
            </div>

        </div>


        <div class="col-lg-5 col-md-5 hidden-sm hidden-xs margin-bottom-35 panelFilmInfo">
            <div class="bg-white">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <div class="pi-img-wrapper">
                            <img class="img-responsive" style="width: 100%" :alt="film.title" :src="baseUrl + 'storage/' + film.poster">
                            <span style="position: absolute; top: 10px; left: 10px;">
                                    <img :src="baseUrl+'Assets/Common/icons/films/' + film.mark +'.png'" class="img-responsive">
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h3 class="bold color1">{{ film.title }}</h3>
                        <h4>{{ __('2D Subtitle') }}</h4>
                    </div>
                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-16">
                        <ul class="list-unstyled padding-left-30 padding-right-30 padding-top-10 padding-bottom-10 font-md font-family-san"
                            style="margin-bottom: 0px;">
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-tags"></i>&nbsp; {{ __('Genre') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ film.genre }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-clock-o"></i>&nbsp; {{ __('Running Time') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ film.running_time }}  {{ __('minutes') }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-16">
                        <hr class="border-dashed border-top-2" style="margin-top: 5px; margin-bottom: 5px;">
                        <ul class="list-unstyled padding-left-30 padding-right-30 padding-top-10 padding-bottom-10 font-md font-family-san">
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-institution"></i>&nbsp; {{ __('Cinema') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">D-Mỹ Đình</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        {{ __('Start Date') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><span class="bold">{{ decodeTime.start_date | moment("DD-MM-YYYY") }}</span></div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><i class="fa fa-clock-o"></i>&nbsp;
                                    {{ __('Start time') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">
                                        {{ decodeTime.start_time }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-desktop"></i>&nbsp;
                                        {{ __('Show Room') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ decodeShow.name }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-cubes"></i>&nbsp;
                                        {{ __('Seats') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><span class="seat-name-selected bold">{{ getSelectedSeats.toString() }}</span></div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center padding-bottom-30">
                            <button type="button" class="btn btn-2 btn-mua-ve btn-thanh-toan" @click="ContinueToPaymentInfo()" style="font-weight: normal;" v-if="!isPreparePayment"><span><i class="fa fa-ticket mr3"></i></span>{{ __('Continue')}}</button>
                            <button type="button" class="btn btn-2 btn-mua-ve btn-back" @click="backToSelectSeats()" style="font-weight: normal;" v-if="isPreparePayment"><span><i class="fa fa-ticket mr3"></i></span>{{ __('Back')}}</button>
                            <button type="button" @click="openConfirm($event)" class="btn btn-2 btn-mua-ve" style="font-weight: normal;" v-if="isPreparePayment"><span><i class="fa fa-ticket mr3"></i></span>{{ __('Continue')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden-md hidden-lg col-sm-16 col-xs-16 margin-bottom-35">
            <div class="bg-white">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <div class="pi-img-wrapper">
                            <img class="img-responsive" style="width: 100%" :alt="film.title"
                                 :src="baseUrl + 'storage/' + film.poster">
                            <span style="position: absolute; top: 10px; left: 10px;">
                                    <img :src="baseUrl+'Assets/Common/icons/films/' + film.mark +'.png'" class="img-responsive">
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h3 class="bold color1">{{ film.title }}</h3>
                        <h4>{{ __('2D Subtitle') }}</h4>
                    </div>
                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-16">
                        <ul class="list-unstyled padding-left-30 padding-right-30 padding-top-10 padding-bottom-10 font-md font-family-san"
                            style="margin-bottom: 0px;">
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-tags"></i>&nbsp; {{ __('Genre') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ film.genre }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-clock-o"></i>&nbsp; {{ __('Running Time') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ film.running_time }}  {{ __('minutes') }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-16">
                        <hr class="border-dashed border-top-2" style="margin-top: 5px; margin-bottom: 5px;">
                        <ul class="list-unstyled padding-left-30 padding-right-30 padding-top-10 padding-bottom-10 font-md font-family-san">
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-institution"></i>&nbsp; {{ __('Cinema') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">D-Mỹ Đình</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-calendar"></i>&nbsp; {{ __('Start Date') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ decodeTime.start_date | moment("DD-MM-YYYY") }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-clock-o"></i>&nbsp;
                                        {{ __('Start time') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ decodeTime.start_time }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-desktop"></i>&nbsp;
                                        {{ __('Show Room') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="bold">{{ decodeShow.name }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="padding-bottom-10 padding-top-10">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <i class="fa fa-cubes"></i>&nbsp;
                                        {{ __('Seats') }}
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><span class="seat-name-selected bold">{{ getSelectedSeats.toString() }}</span></div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center padding-bottom-30">
                            <button type="button" class="btn btn-2 btn-mua-ve btn-thanh-toan" @click="ContinueToPaymentInfo()" style="font-weight: normal;" v-if="!isPreparePayment"><span><i class="fa fa-ticket mr3"></i></span>{{ __('Continue')}}</button>
                            <button type="button" class="btn btn-2 btn-mua-ve btn-back" @click="backToSelectSeats()" style="font-weight: normal;" v-if="isPreparePayment"><span><i class="fa fa-ticket mr3"></i></span>{{ __('Back')}}</button>
                            <button type="button" @click="openConfirm($event)" class="btn btn-2 btn-mua-ve" style="font-weight: normal;" v-if="isPreparePayment"><span><i class="fa fa-ticket mr3"></i></span>{{ __('Continue')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import store from '../../store/modules/booking/index'
    import { mapGetters } from 'vuex'

    export default {
        name: "SelectSeats",
        store: store,
        data () {
            return {
                isPreparePayment: false,
                isClicking: false,
            }
        },
        props: [
            "baseUrl",
            "filmName",
            "homeRoute",
            "filmId",
            "markAlert",
            "time",
            "show",
            "seats",
            "bookedSeats",
            "user",
            "combos"
        ],
        mounted() {
            Echo.join(`new-join.${this.decodeTime.id}`)
                .here((users) => {
                })
                .joining((user) => {
                    let seats = this.selectedSeats,
                        data = [];
                    seats.forEach(seat => {
                        let item = {};
                        item.seat = seat.seat.id;
                        item.user = seat.user.id;
                        data.push(item);
                    });
                    if (seats.length > 0) {
                        axios.post(route('bookings.api.sendSelectedSeatsToJoiner', {joiner: user.id, data: data, time: this.decodeTime.id}))
                            .then(res => res)
                            .catch(err => {
                                console.log(err);
                            });
                    }
                })
                .leaving((user) => {
                    this.$store.dispatch('removeLeavingUserHoldSeats', user);
                });
            Echo.private(`time.${this.decodeTime.id}`)
                .listen(".time", e => {
                    let seat = [e.seat];
                    this.$store.dispatch('setHoldSeat', seat);

                });
            Echo.private(`customer.join.${this.authUser.id}`)
                .listen('.customer.join', e => {
                    if (e.seats.length > 0) {
                        this.$store.dispatch('setHoldSeat', e.seats);
                    }
                });
            Echo.private(`time.newbooking.${this.decodeTime.id}`)
                .listen('.time_newBooking', e => {
                    let seats = (e.seats);
                    this.$store.dispatch('setSoldSeat', seats);
                });
        },
        created() {
            countdown('.time-et', 10);
            this.$store.dispatch('setFilm', this.filmId);
            this.$store.dispatch('initSoldSeats', this.bookedSeats);
        },
        computed: {
            ...mapGetters([
                'film',
                "getHoldSeatsId",
                "getHoldSeats",
                'selectedSeats',
                'getSelectedSeats',
                'getSelectedSeatsId',
                'selectedNormal',
                'totalAmount',
                'selectedVIP',
                'selectedDouble',
                'selectedCombo',
                'paymentMethod',
                'holdSeats',
                'getSoldSeats'
            ]),
            totalAmountLabel () {
                let amount = parseFloat(this.$store.getters.totalAmount);
                if (this.$store.getters.selectedCombo.price !== undefined) {
                    amount += parseFloat(this.$store.getters.selectedCombo.price);
                }
                return amount + ' vnđ';
            },
            selectedNormalLabel () {
                return this.$store.getters.selectedNormal.length + 'x45000 vnđ';
            },
            selectedVIPLabel () {
                return this.$store.getters.selectedVIP.length + 'x55000 vnđ';
            },
            selectedDoubleLabel () {
                return this.$store.getters.selectedDouble.length + 'x90000 vnđ';
            },
            decodeMarkAlert () {
                return JSON.parse(this.markAlert);
            },
            decodeTime () {
                return JSON.parse(this.time);
            },
            decodeShow () {
                return JSON.parse(this.show);
            },
            decodeSeats () {
                return JSON.parse(this.seats);
            },
            decodeCombos () {
                return JSON.parse(this.combos);
            },
            authUser () {
                return JSON.parse(this.user);
            }
        },
        methods: {
            getSelectClass(seat) {
                let classes = [];
                if (!this.getHoldSeatsId.includes(seat.id) && this.getSelectedSeatsId.includes(seat.id)) {
                    classes.push('seat-select');
                }
                if (this.getHoldSeatsId.includes(seat.id) && !this.getSelectedSeatsId.includes(seat.id)) {
                    classes.push('seat-hold');
                }

                if (!this.getSelectedSeatsId.includes(seat.id) &&
                    !this.getSoldSeats.includes(seat.id) &&
                    !this.getHoldSeatsId.includes(seat.id)
                ) {
                    classes.push('seat-empty');
                }
                switch (seat.type) {
                    case 0:
                        classes.push('seat-normal');
                        break;
                    case 1:
                        classes.push('seat-vip');
                        break;
                    case 2:
                        classes.push('seat-double');
                        break;
                    default:
                }

                if (this.getSoldSeats.includes(seat.id)) {
                    classes.push('seat-sold');
                }

                return classes;
            },
            selectCombo: function(event, action, combo) {
                if (action === 'select') {
                    this.$store.dispatch('setCombo', combo);
                } else {
                    this.$store.dispatch('deleteCombo', combo);
                }
            },
            selectSeat: function (seat) {
                if (this.isClicking) {
                    return;
                }
                this.isClicking = true;

                setTimeout(function () {
                    if (!this.getHoldSeatsId.includes(seat.id) && !this.getSoldSeats.includes(seat.id)) {
                        let seatData = {
                            seat: seat.id,
                            user: this.authUser.id
                        };
                        axios.post(route('bookings.api.selectSeat', {seat: seatData, time: this.decodeTime.id}))
                            .then(res => res)
                            .catch(err => {
                                alert('Please dont click super fast');
                            });
                        this.$store.dispatch('setSeats', {seat: seat, user: this.authUser});
                    }
                    this.isClicking = false;
                }.bind(this), 250);
            },
            ContinueToPaymentInfo: function () {
                if (this.$store.getters.getSelectedSeats.length > 0) {
                    this.isPreparePayment = true;
                }
            },
            backToSelectSeats: function () {
                this.isPreparePayment = false;
            },
            setPaymentMethod: function (method) {
                let langSelector = $('.lang-text'),unavailableMethodText = langSelector.attr('unavailable-method');
                if (method === 'vnpay') {

                    alert(unavailableMethodText);
                    $('#card1').trigger('click');
                } else {
                    this.$store.dispatch('setPaymentMethod', method);
                }

            },
            openConfirm (event) {
                let self = this;
                let store = this.$store;
                let langSelector = $('.lang-text'),
                    confirmToPaymentTitle = langSelector.attr('confirm-to-payment-title'),
                    confirmToPaymentText = langSelector.attr('confirm-to-payment-text'),
                    confirmToPaymentRecheck = langSelector.attr('confirm-to-payment-recheck'),
                    confirmToPaymentConfirm = langSelector.attr('confirm-to-payment-confirm');
                // Open customized confirmation dialog window
                $.fancyConfirm({
                    title     : confirmToPaymentTitle,
                    message   : confirmToPaymentText,
                    okButton  : confirmToPaymentConfirm,
                    noButton  : confirmToPaymentRecheck,
                    callback  : function (value) {
                        if (value) {
                            store.dispatch('setPackages', self.decodeTime);
                            let data = self.$store.getters.allPackages;
                            axios.post(route('bookings.api.getPayment', data))
                                .then(res => {
                                    window.location.replace(res.data);
                                })
                                .catch(err => {
                                    console.error(err);
                                });
                        }
                    }
                });
            }
        },
    }
</script>

<style scoped>

</style>
