@extends('superadmin.admin-layout')
@section('content')
<div class="page-header">
    <h3 class="page-title">Add utilisateur Administration</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      </ol>
    </nav>
  </div>
  <div class="row">          
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          
          <form class="form-sample" method="post" action="{{route('addvendeur')}}">
            @csrf
            
            <p class="card-description">Ajouter User</p>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Fullname</label>
                  <div class="col-sm-9">
                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="non konplè" />
                    <span class="error">@error('name') 
                       {{$message}}
                      @enderror</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Adresse</label>
                  <div class="col-sm-9">
                    <input type="text" name="address" value="{{old('address')}}" class="form-control" placeholder="adrès vandè a" />
                    <span class="error">@error('address') 
                      {{$message}}
                     @enderror</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">role</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="gender" value="{{old('gender')}}">
                      <option>admin</option>
                      <option>admin2</option>
                      <option>addeur</option>
                      <option>comptable</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Telefon</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" value="{{old('phone')}}"  placeholder="Telefon vandè a" name="phone" />
                    <span class="error">@error('phone') 
                      {{$message}}
                     @enderror</span>
                  </div>
                </div>
              </div>
            </div>
           
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">username</label>
                  <div class="col-sm-9">
                    <input type="text" name="username" value="{{old('username')}}"class="form-control" placeholder="470000" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">password</label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control" value="{{old('password')}}" name="password" placeholder=""/>
                    <span class="error">@error('password') 
                      {{$message}}
                     @enderror</span>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="row">
             
             
              <button type="submit" class="btn btn-gradient-primary mb-2">Anrejistre</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>