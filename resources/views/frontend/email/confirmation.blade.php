<!DOCTYPE html>
<html>
<head>
  <style>
    /* Add some basic styling to the table */
    table {
      border-collapse: collapse;
      width: 100%;
      border-radius: 10px;
      box-shadow: rgba(0, 0, 0, 0.15) 0px 3px 7px 0px;
      border: 1px solid #eee;
      overflow: hidden;
    }

    th, td {
      border: 1px solid #eee;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

    
<p>Dear {{ $user->fullname }},</p>

<p>We are writing to provide you with comprehensive details regarding your recent remittance with the MTCN number: {{ $trx_id }}. Ensuring transparency and clarity in our communication is paramount, and we are pleased to share the following information with you:</p>

<h5>Remittance Summary</h5>
<table>
  <tr>
    <td>Sending Amount</td>
    <td>{{ $identifier_data['data']->send_money }} {{ $identifier_data['data']->sender_currency }}</td>
    <td>Exchange Rate</td>
    <td>{{ $identifier_data['data']->sender_ex_rate }} {{ $identifier_data['data']->sender_currency }} = {{ $identifier_data['data']->receiver_ex_rate }} {{ $identifier_data['data']->receiver_currency }}</td>
  </tr>
  <tr>
    <td>Total Fees and Charges</td>
    <td>{{ getAmount($data['amount']->total_charge, 2) }} {{ $identifier_data['data']->sender_currency }}</td>
    <td>Amount We’ll Convert</td>
    <td>{{ getAmount($identifier_data['data']->convert_amount, 2) }} {{ $identifier_data['data']->sender_currency }}</td>
  </tr>
  <tr>
    <td>Will Get Amount</td>
    <td>{{ getAmount($data['amount']->will_get, 2) }} {{ $identifier_data['data']->receiver_currency }}</td>
    <td>Sending Purpose</td>
    <td>{{ $identifier_data['data']->sending_purpose->name }}</td>
  </tr>
  <tr>
    <td>Source of Fund</td>
    <td>{{ $identifier_data['data']->source->name }}</td>
  </tr>
</table>

<h5>Recipient Summary</h5>

<table>
  <tr>
    <td>Name</td>
    <td>{{ $identifier_data['data']->first_name }} {{ $identifier_data['data']->last_name }}</td>
    <td>Phone</td>
    <td>{{ $identifier_data['data']->phone }}</td>
  </tr>
  <tr>
    <td>Email</td>
    <td>{{ $identifier_data['data']->email }}</td>
    <td>Address</td>
    <td>{{ $identifier_data['data']->address ?? 'N/A'}}</td>
  </tr>
  <tr>
    <td>Country</td>
    <td>{{ $identifier_data['data']->country }}</td>
  </tr>
</table>

<h5>Payment Summary</h5>

<table>
  <tr>
    <td>Transaction Type</td>
    <td>{{ $identifier_data->type }}</td>
    <td>Method Name</td>
    <td>{{ $identifier_data['data']->method_name }}</td>
  </tr>
  <tr>
    <td>Account Number</td>
    <td>{{ $identifier_data['data']->account_number }}</td>
    <td>Payment Method</td>
    <td>{{ $data['currency']['name'] }}</td>
  </tr>
  <tr>
    <td>Exchange Rate</td>
    <td>1 {{ $data['amount']->default_currency }} = {{ getAmount($identifier_data['data']->currency->rate) }} {{ $identifier_data['data']->currency->code }}</td>
    <td>Total Payable Amount</td>
    <td>{{ getAmount($data['amount']->total_amount, 2) }} {{ $data['amount']->sender_cur_code }}</td>
  </tr>
</table>

<p>We believe that providing these detailed breakdowns will give you a clear understanding of the remittance process and the associated particulars. Should you have any questions, require further assistance, or notice any discrepancies, please do not hesitate to reach out to our dedicated support team at {{ $contact->value->email }}.</p>

<p>Your satisfaction and trust are of utmost importance to us, and we are committed to ensuring a seamless and secure remittance experience for you. Thank you for choosing us as your trusted partner for your financial needs.</p>
<p>Best Regards</p>
<p>{{ $basic_settings->site_name }}</p>

</body>
</html>
