@extends('superadmin.admin-layout')


@section('content')
<div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Nouveau Compagnie</h4>
        <p class="card-description">  </p>
        <form class="forms-sample" action="{{route('add_compagnie')}}" method="POST">
            @csrf
          <div class="form-group">
            <label for="exampleInputUsername1">Nom Compagnie</label>
            <input type="text" class="form-control" name="compagnie" id="InputNomcompagnie" placeholder="Nom capagnie" minlength="4" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">adresse</label>
            <input type="text" class="form-control" name="adress" id="Inputadresse" placeholder="Adresse" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">City</label>
            <input type="text" class="form-control" name="city" id="InputCity" placeholder="city" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Email Compagnie</label>
            <input type="text" class="form-control" name="email" id="InputEmail1" placeholder="email compagnie" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Phone Compagnie</label>
            <input type="text" class="form-control" name="phone" id="InputPhone" placeholder="phone">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Plan</label>
            <select name="plan" class="form-control">
                <option value="10">10</option>
                <option value="8">8</option>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">username</label>
            <input type="text" class="form-control" name="user" id="username" placeholder="user">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="passowrd">
          </div>
          
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="/wp-admin/admin"><button class="btn btn-light">Cancel</button></a>
        </form>
      </div>
    </div>
  </div>
@endsection
