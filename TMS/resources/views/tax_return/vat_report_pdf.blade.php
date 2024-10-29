<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAT Report PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 6.49pt;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .header {
            border: 1px solid #000;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .small {
            font-size: 6pt;
        }

        .medium {
            font-size: 8pt;
            font-weight: bold;
        }

        .title {
            font-size: 12pt;
            font-weight: bold;
        }

        .form-number {
            font-size: 14pt;
            font-weight: bold;
        }

        .form-container {
            width: 100%;
  
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 2pt;
        }

        .form-table td {
            border: 1px solid #000;
            padding: 0;
            vertical-align: top;
            line-height:0.8;
        }

        input[type="text"] {
            border: 1px solid #000;
            padding: 0;
            font-size: 7pt;
            height: 7pt;
            margin: 2pt 0;
        }
        input[type="radio"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 8px;       /* Adjust size as needed */
  height: 8px;      /* Adjust size as needed */
  border-radius: 50%;      /* Make it round */
  background-color: transparent; /* No background */

}

/* Add styling for when the radio button is selected */
input[type="radio"]:checked {
  background-color: #333;  /* Dark color when selected */
  border-color: #333;
}

        .amount-input {
            text-align: right;
            width: 100pt;
        }

        .text-right {
            text-align: right;
        }

        .section-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .indent {
            padding-left: 15pt;
        }
        tr{
            padding:0;
            margin:0;
        }
        td{
            padding:0;
            margin:0;
            
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <!-- Left Section -->
                <td style="width: 30%;">
                    <table>
                        <tr>
                            <td >
                                <img src="{{ public_path('images/Bureau_of_Internal_Revenue_(BIR).png') }}" alt="BIR Logo" style="width: 40px; height:40px;">
                            </td>
                            <td style="padding-left: 5pt;">
                                <span class="small">Republika ng Pilipinas</span><br>
                                <span class="small">Kagawaran ng Pananalapi</span><br>
                                <span class="medium" style="white-space: nowrap;">Kawanihan ng Rentas Iternas</span>
                            </td>
                        </tr>
                    </table>
                </td>
                
                <!-- Middle Section -->
                <td style="width: 38%; text-align: center;">
                    <span class="title">Quarterly Value-Added<br>Tax Return</span><br>
                    <span class="medium">(Cumulative for 3 Months)</span>
                </td>
    
                <!-- Spacer -->
                <td style="width: 10%;"></td>
    
                <!-- Right Section -->
                <td style="width: 15%; text-align: left;">
                    <span class="small">BIR Form No.</span><br>
                    <span class="form-number">2550Q</span><br>
                    <span class="small">February 2007 (ENCS)</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="form-container">
        <form>
   
            <table class="form-table">
                <tr>
                    <td width="24%">
                        Year Ended
                        <input type="radio" name="calendar" value="2" checked> Calendar
                        <input type="radio" name="calendar" value="1"> Fiscal<br> <br>
                        (MM/YYYY)
                        <input type="text" name="month" style="width: 20pt;" value="12">
                        <input type="text" name="year" style="width: 30pt;" value="2024">
                    </td>
                    <td width="20%">
                        2 Quarter<br>
                        <input type="checkbox" name="quarter_1"> 1st
                        <input type="checkbox" name="quarter_2"> 2nd
                        <input type="checkbox" name="quarter_3" checked> 3rd
                        <input type="checkbox" name="quarter_4"> 4th
                    </td>
                    <td width="30%" colspan="2">
                        3 Return Period (MM/DD/YYYY)<br>
                        From: <input type="text" name="period_from" value="10/01/2024" style="width: 30pt;">
                        To: <input type="text" name="period_to" value="12/31/2024" style="width: 32pt;">
                    </td>
       
                    <td colspan="2">
                        4 Amended Return?
                        <input type="checkbox" name="amended_yes"> Yes
                        <input type="checkbox" name="amended_no" checked> No
                    </td>
                    <td colspan="2">
                        5 Short Period Return?
                        <input type="checkbox" name="short_period_yes"> Yes
                        <input type="checkbox" name="short_period_no" checked> No
                    </td>
                </tr>
            </table>
            <!-- Section 6-13 -->
            <table class="form-table">
                <tr>
                    <td width="33%">
                     <b>   6 </b> TIN
                        <input type="text" name="tin" value="222 222 222 222" style="width: 70%;">
                    </td>
                    <td width="33%">
                        7 RDO Code<br>
                        <input type="text" name="rdo_code" value="005" style="width: 90%;">
                    </td>
                    <td width="34%">
                        8 No. of Sheets Attached<br>
                        <input type="text" name="sheets_attached" value="0" style="width: 90%;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        9 Line of Business<br>
                        <input type="text" name="business_line" value="OFFICE APPLIANCES" style="width: 95%;">
                    </td>
                    <td>
                        11 Telephone No.<br>
                        <input type="text" name="telephone" value="09112223344" style="width: 90%;">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        10 Taxpayer's Name<br>
                        <input type="text" name="taxpayer_name" value="DAPARADA LAMA" style="width: 97%;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        12 Registered Address<br>
                        <input type="text" name="registered_address" value="DOON SA STREET NA YON 4545, MINDANAO" style="width: 95%;">
                    </td>
                    <td>
                        13 Zip Code<br>
                        <input type="text" name="zip_code" value="4012" style="width: 90%;">
                    </td>
                </tr>
            </table>

            <!-- Section 14 -->
            <table class="form-table">
                <tr>
                    <td>
                        14 Are you availing of tax relief under Special Law / International Tax Treaty?
                        <input type="checkbox" name="tax_relief_yes"> Yes
                        <input type="checkbox" name="tax_relief_no" checked> No
                        If yes, specify: <input type="text" name="tax_relief_specify" style="width: 200pt;">
                    </td>
                </tr>
            </table>

            <!-- Sales/Receipts Section -->
            <table class="form-table">
                <tr class="section-header">
                    <td colspan="3">Sales/Receipts and Output Tax</td>
                </tr>
                <tr>
                    <td width="60%">15 Vatable Sales/Receipt - Private (Schedule 1)</td>
                    <td width="20%" class="text-right">
                        <input type="text" name="vatable_sales" value="2,220.00" class="amount-input">
                    </td>
                    <td width="20%" class="text-right">
                        <input type="text" name="vatable_output_tax" value="266.40" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td>16 Sales to Government</td>
                    <td class="text-right">
                        <input type="text" name="govt_sales" value="2.00" class="amount-input">
                    </td>
                    <td class="text-right">
                        <input type="text" name="govt_output_tax" value="2.00" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td>17 Zero Rated Sales/Receipts</td>
                    <td class="text-right">
                        <input type="text" name="zero_rated_sales" value="2.00" class="amount-input">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>18 Exempt Sales/Receipts</td>
                    <td class="text-right">
                        <input type="text" name="exempt_sales" value="2.00" class="amount-input">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>19 Total Sales/Receipts and Output Tax Due</td>
                    <td class="text-right">
                        <input type="text" name="total_sales" value="2,226.00" class="amount-input">
                    </td>
                    <td class="text-right">
                        <input type="text" name="total_output_tax" value="268.40" class="amount-input">
                    </td>
                </tr>
            </table>

            <!-- Less: Input Tax -->
            <table class="form-table">
                <tr class="section-header">
                    <td colspan="3">Less: Allowable Input Tax</td>
                </tr>
                <tr>
                    <td colspan="2">20A Input Tax Carried Over from Previous Period</td>
                    <td class="text-right">
                        <input type="text" name="input_tax_carried" value="2.00" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">20B Input Tax Deferred on Capital Goods Exceeding P1Million from Previous Period</td>
                    <td class="text-right">
                        <input type="text" name="input_tax_deferred" value="2.00" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">20C Transitional Input Tax</td>
                    <td class="text-right">
                        <input type="text" name="transitional_input_tax" value="22.00" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">20D Presumptive Input Tax</td>
                    <td class="text-right">
                        <input type="text" name="presumptive_input_tax" value="2.00" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">20E Others</td>
                    <td class="text-right">
                        <input type="text" name="others_input_tax" value="2.00" class="amount-input">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">20F Total (Sum of Items 20A, 20B, 20C, 20D & 20E)</td>
                    <td class="text-right">
                        <input type="text" name="total_input_tax" value="30.00" class="amount-input">
                    </td>
                </tr>
            </table>

            <!-- Current Transactions -->
            <table class="form-table">
                <tr class="section-header">
                    <td colspan="3">21 Current Transactions</td>
                </tr>
                <tr>
                    <td>A/B Purchase of Capital Goods not exceeding P1Million (Schedule 2)</td>
                    <td class="text-right">
                        <input type="text" name="capital_goods_amount" value="0.00" class="amount-input">
                    </td>
                    <td class="text-right">
                        <input type="text" name="capital_goods_tax" value="0.00" class="amount-input">
                    </td>
                </tr>
                <!-- Continue with remaining fields... -->
            </table>

            <!-- Continue with remaining sections... -->
        </form>
    </div>
</body>
</html>