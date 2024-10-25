<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAT Report PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
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

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header img {
            height: 40pt;
            width: 40pt;
        }

        table.main-form {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5pt;
        }

        table.main-form td {
            border: 1px solid black;
            padding: 2pt;
            font-size: 8pt;
        }

        .form-input {
            border: 1px solid black;
            height: 15pt;
        }

        .small-text {
            font-size: 8pt;
        }

        .checkbox-group {
            font-size: 8pt;
            line-height: 1.2;
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
                                <td style="width: 40pt;">
                                    <img src="{{ public_path('images/Bureau_of_Internal_Revenue_(BIR).png') }}" alt="BIR Logo">
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
        <div class="form-section" style="margin-top: 5pt; font-family: Arial, sans-serif;">
            <!-- First instruction line -->
            <div style="background-color: #f0f0f0; padding: 2pt; font-size: 8pt; border: 1px solid black;">
                Fill in all applicable spaces. Mark all appropriate boxes with an "X".
            </div>
        
            <form>
                <table class="main-form">
                    <tr>
                        <!-- Year Ended Section -->
                        <td style="width: 40%;">
                            <div class="small-text">1 Year Ended</div>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border: none;">
                                        <input type="radio" name="year_type" value="calendar" checked> Calendar
                                        <input type="radio" name="year_type" value="fiscal"> Fiscal
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none;">
                                        <div class="small-text">(MM/YYYY)</div>
                                        <input type="text" name="month" class="form-input" value="12" style="width: 20%;">
                                        <input type="text"name="year" class="form-input" value="2024" style="width: 80%;">
                                    </td>
                                </tr>
                            </table>
                        </td>
            
                        <!-- Quarter Section -->
                        <td style="width: 15%;">
                            <div class="small-text">2 Quarter</div>
                            <div class="checkbox-group">
                                <input type="radio" name="quarter" value="1"> 1st
                                <input type="radio" name="quarter" value="2"> 2nd<br>
                                <input type="radio" name="quarter" value="3"> 3rd
                                <input type="radio" name="quarter" value="4" checked> 4th
                            </div>
                        </td>
            
                        <!-- Return Period Section -->
                        <td style="width: 25%;">
                            <div class="small-text">3 Return Period(MM/DD/YYYY)</div>
                            <div class="checkbox-group">
                                From: <input type="text" class="form-input" value="10/01/2024" style="width: 80pt;"><br>
                                To: <input type="text" class="form-input" value="12/31/2024" style="width: 80pt;">
                            </div>
                        </td>
            
                        <!-- Amended Return Section -->
                        <td style="width: 15%;">
                            <div class="small-text">4 Amended Return?</div>
                            <div class="checkbox-group">
                                <input type="radio" name="amended" value="yes"> Yes
                                <input type="radio" name="amended" value="no" checked> No
                            </div>
                        </td>
            
                        <!-- Short Period Section -->
                        <td style="width: 20%;">
                            <div class="small-text">5 Short Period Return?</div>
                            <div class="checkbox-group">
                                <input type="radio" name="short_period" value="yes"> Yes
                                <input type="radio" name="short_period" value="no" checked> No
                            </div>
                        </td>
                    </tr>
            
                    <!-- TIN and Business Info Row -->
                    <tr>
                        <!-- TIN Section -->
                        <td colspan="3">
                            <div class="small-text">6 TIN</div>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="border: none; width: 25%;"><input type="text" class="form-input" value="222" style="width: 90%; text-align: center;"></td>
                                    <td style="border: none; width: 25%;"><input type="text" class="form-input" value="222" style="width: 90%; text-align: center;"></td>
                                    <td style="border: none; width: 25%;"><input type="text" class="form-input" value="222" style="width: 90%; text-align: center;"></td>
                                    <td style="border: none; width: 25%;"><input type="text" class="form-input" value="222" style="width: 90%; text-align: center;"></td>
                                </tr>
                            </table>
                        </td>
            
                        <!-- RDO Code -->
                        <td>
                            <div class="small-text">7 RDO Code</div>
                            <input type="text" class="form-input" value="005" style="width: 50pt;">
                        </td>
            
                        <!-- No. of Sheets -->
                        <td>
                            <div class="small-text">8 No. of Sheets Attached</div>
                            <input type="text" class="form-input" value="0" style="width: 30pt;">
                        </td>
            
                        <!-- Line of Business -->
                        <td style="background-color: #f0f0f0;">
                            <div class="small-text">9 Line of Business</div>
                            <input type="text" class="form-input" value="OFFICE APPLIANCES" style="width: 95%;">
                        </td>
                    </tr>
            
                    <!-- Taxpayer Info Row -->
                    <tr>
                        <td colspan="5">
                            <div class="small-text">10 Taxpayer's Name</div>
                            <input type="text" class="form-input" value="DAPARADA LAMA" style="width: 98%;">
                        </td>
                        <td style="background-color: #f0f0f0;">
                            <div class="small-text">11 Telephone No.</div>
                            <input type="text" class="form-input" value="09112223344" style="width: 95%;">
                        </td>
                    </tr>
            
                    <!-- Address Row -->
                    <tr>
                        <td colspan="5">
                            <div class="small-text">12 Registered Address</div>
                            <input type="text" class="form-input" value="DOON SA STREET NA YON 4545, MINDANAO" style="width: 98%;">
                        </td>
                        <td style="background-color: #f0f0f0;">
                            <div class="small-text">13 Zip Code</div>
                            <input type="text" class="form-input" value="4012" style="width: 95%;">
                        </td>
                    </tr>
            
                    <!-- Tax Relief Row -->
                    <tr>
                        <td colspan="6">
                            <div class="small-text">
                                14 Are you availing of tax relief under Special Law / International Tax Treaty?
                                <input type="radio" name="tax_relief" value="yes"> Yes
                                <input type="radio" name="tax_relief" value="no" checked> No
                                If yes, specify <input type="text" class="form-input" style="width: 200pt;">
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
            <div class="footer">
                <p>This report was generated on {{ date('F j, Y') }}</p>
            </div>
        </div>
    </body>
    </html>
