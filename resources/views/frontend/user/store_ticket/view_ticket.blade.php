@extends('frontend.layouts.user_panel')

@section('panel_content')
@php
$date=date('jS F, Y',strtotime($ticketDetails->created_at));
$daysName=date('D',strtotime($ticketDetails->created_at));
$fullDate=$date.' '.$daysName;
$time= date('g:i a',strtotime($ticketDetails->created_at));
$qrCode=asset('public/uploads/ticket_qr/'.$ticketDetails->ticket_qrcode_image);
$logo=asset('public/uploads/logo/logo.png');
@endphp

<body width="100%" style="margin: 0; padding: 30px 0 !important; mso-line-height-rule: exactly; background-color: #f5f5f5; font-family: sans-serif; color: #676767; -webkit-print-color-adjust: exact;">
    <center style="width: 100%; background-color: #f5f5f5;">
        <div style="max-width: 428px; margin: 0 auto; background: #f5f5f5;">
            <!-- BEGIN BODY -->
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                <tr>
                    <td valign="top" class="bg_white" style="padding: 20px 20px 0; background: #fff;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 15px;">
                            <tr>
                                <td style="text-align: left; padding: 10px 15px;">
                                    <h4 style="color:#000;margin-bottom: 5px;margin-top:0;font-size: 18px;font-weight: 600;">{{ translate('Venue')}}</h4>
                                    <p style="color:#000;font-size: 14px;margin: 0;">{{$ticketDetails->address}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="bg_white" style="padding: 0px 20px; background: #fff;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 15px;">
                            <tr>
                                <td style="text-align: left; padding: 10px 15px;">
                                    <h4 style="color:#000;margin-bottom: 5px;margin-top:0;font-size: 18px;font-weight: 600;">{{ translate('Email')}}</h4>
                                    <p style="color:#000;font-size: 14px;margin: 0;">{{$ticketDetails->email}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="bg_white" style="padding: 0px 20px; background: #fff;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 15px;">
                            <tr>
                                <td style="text-align: left; padding: 10px 15px;">
                                    <h4 style="color:#000;margin-bottom: 5px;margin-top:0;font-size: 18px;font-weight: 600;">{{ translate('Phone')}}</h4>
                                    <p style="color:#000;font-size: 14px;margin: 0;">{{$ticketDetails->phone}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 20px 20px; background-color: #fff;border: 2px solid #fff;">

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 0 20px;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td valign="center" width="20%" style="padding: 10px 0;">
                                                <a href="#" style="width: 100%;">
                                                    <img src="{!! $qrCode !!}" alt="Tyler Bistro Marble Top Side Table" style="width: 100px; border-radius: 5px;" />
                                                </a>
                                            </td>
                                            <td valign="middle" align="left" width="80%" style="padding: 10px 0 10px 15px;">
                                                <h3 style="font-size: 13px; margin: 0 0 5px;">
                                                    <a href="#" style="color: #4c4c4c; text-decoration: none;">
                                                        {{$ticketDetails->name}}
                                                    </a>
                                                </h3>
                                                <div>
                                                    <p style="margin: 0 0 5px; font-size: 13px; color: #4c4c4c; font-weight: 600;">
                                                        {{ translate('Ticket ID')}} : <strong>{{$ticketDetails->ticket_number}}</strong>
                                                    </p>
                                                </div>
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #f1f1f1;"></table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="bg_white email-section" style="padding: 0 20px 20px; background-color: #fff;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="" style="background: #fff;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 0 auto;">
                                        <tr>
                                            <td width="100%" valign="center" style="padding: 15px 0px 0; border-top: 1px solid #666666;">
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="center" style="padding: 15px 10px; font-size: 12px; color: #666666;"><strong>{{$fullDate}} {{$time}}</strong></td>
                                                        <td valign="center" align="right" style="padding: 15px 10px; font-size: 15px; color: #666666; font-weight: 600;">
                                                            <a href="">
                                                                <img src="{!! $logo !!}" alt="Tyler Bistro Marble Top Side Table" style="width:20%; border-radius: 5px;" style="height: 60px;" />
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
        <a href="{{route('ticket.index')}}" class="btn btn-primary mt-3 text-white">{{ translate('Back')}}</a>
    </center>
    @endsection
