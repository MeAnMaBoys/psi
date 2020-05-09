<?php namespace App\Controllers;

use App\Models\Izvodjac;
use App\Models\Organizator;
use App\Models\Korisnik;
use App\Models\Posetilac;
use \Config\Services\Email;

class Gost extends BaseController
{
    protected function prikaz($page,$data)
    {
        $data['controller']='gost';

        echo view('header_gost');
        echo view($page,$data);
        echo view('footer');
    }

    public function moj_nalog()
    {
        $this->prikaz('moj_nalog',[]);
    }

    public function registracija()
    {
        $this->prikaz('registracija',[]);
    }

    public function registracija_izvodjac()
    {
        $this->prikaz('registracija_izvodjac',[]);
    }

    public function registracija_organizator()
    {
        $this->prikaz('registracija_organizator',[]);
    }

    public function registracija_posetilac()
    {
        $this->prikaz('registracija_posetilac',[]);
    }


    private function proveri_podatke_posetilac()
    {
        $data=[];
        $data['email']='is-valid';
        $data['username']='is-valid';

        $data['email_val']=$this->request->getPost('email');
        $data['username_val']=$this->request->getPost('username');
        $data['password_val']=$this->request->getPost('password');

        $flag = false;
        if(preg_match("/^\w+@\w+([\.-]\w+)*(\.\w{2,3})+$/",$this->request->getPost('email'))==0)
        {
            $data['email']="is-invalid";
            $flag = true;
            $data['email_val']="";
        }
        if((preg_match("/[^a-zA-Z0-9]/",$this->request->getPost('username'))==1)||(strlen($this->request->getPost('username'))<3)||(strlen($this->request->getPost('username'))>12))
        {
            $data['username']='is-invalid';
            $flag = true;
            $data['username_val']="";
        }

        if(strcmp($this->request->getPost('password'),$this->request->getPost('password_cf'))!=0)
        {
            $data['password_cf']="is-invalid";
            $flag=true;
        }
        $korModel = new Korisnik();

        $row = $korModel->where('Email',$data['email_val'])->first();

        $data['enter']=false;
        if(($row)!=null)
        {
            $data['email']="is-invalid";
            $flag = true;
            $data['enter']=true;
        }

        $ret = ['data'=>$data,'flag'=>$flag];
        return $ret;
    }

    protected function proveri_podatke()
    {

        $data=[];
        $data['email']='is-valid';
        $data['name']='is-valid';
        $data['surename']='is-valid';
        $data['username']='is-valid';
        $data['phone']='is-valid';

        $data['email_val']=$this->request->getPost('email');
        $data['name_val']=$this->request->getPost('name');
        $data['surename_val']=$this->request->getPost('surename');
        $data['username_val']=$this->request->getPost('username');
        $data['phone_val']=$this->request->getPost('phone');
        $data['password_val']=$this->request->getPost('password');

        $flag = false;
        if(preg_match("/^\w+@\w+([\.-]\w+)*(\.\w{2,3})+$/",$this->request->getPost('email'))==0)
        {
            $data['email']="is-invalid";
            $flag = true;
            $data['email_val']="";
        }
        if((preg_match("/[^a-zA-Z]+/",$this->request->getPost('name'))==1)||(strlen($this->request->getPost('name'))<2)||(strlen($this->request->getPost('name'))>15))
        {
            $data['name']='is-invalid';
            $flag = true;
            $data['name_val']="";
        }
        if((preg_match("/[^a-zA-Z]+/",$this->request->getPost('surename'))==1)||(strlen($this->request->getPost('surename'))<2)||(strlen($this->request->getPost('surename'))>15))
        {
            $data['surename']='is-invalid';
            $flag = true;
            $data['surename_val']="";
        }
        if((preg_match("/[^a-zA-Z0-9]/",$this->request->getPost('username'))==1)||(strlen($this->request->getPost('username'))<3)||(strlen($this->request->getPost('username'))>12))
        {
            $data['username']='is-invalid';
            $flag = true;
            $data['username_val']="";
        }
        if((preg_match("/[^0-9]/",$this->request->getPost('phone'))==1)||(strlen($this->request->getPost('phone'))<9)||(strlen($this->request->getPost('phone'))>12))
        {
            $data['phone']='is-invalid';
            $flag = true;
            $data['phone_val']="";
        }

        if(strcmp($this->request->getPost('password'),$this->request->getPost('password_cf'))!=0)
        {
            $data['password_cf']="is-invalid";
            $flag=true;
        }
        $korModel = new Korisnik();

        $row = $korModel->where('Email',$data['email_val'])->first();

        $data['enter']=false;
        if(($row)!=null)
        {
            $data['email']="is-invalid";
            $flag = true;
            $data['enter']=true;
        }

        $ret = ['data'=>$data,'flag'=>$flag];
        return $ret;
    }

    protected function kreiraj_korisnika($vector)
    {
        $korModel = new Korisnik();
        $korModel->ubaci_el($vector['data']);
        $row = $korModel->where('Email',$vector['data']['email_val'])->first();

        return $row;
    }

    protected function kreiraj_organizatora($vector)
    {
        $orgModel = new Organizator();
        $orgModel->ubaci_el($vector['data']);
    }

    protected function kreiraj_posetioca($vector)
    {
        $posModel = new Posetilac();
        $posModel->ubaci_el($vector['data']);
    }
    protected function kreiraj_izvodjaca($vector)
    {
        $izvModel = new Izvodjac();
        $izvModel->ubaci_el($vector['data']);
    }

    public function registruj_izvodjac()
    {   
        $vector = $this->proveri_podatke();
        if($vector['flag']==true)
        {
            $this->prikaz('registracija_izvodjac',$vector['data']);
            $this->unsetAll();
            return;
        }
        $row = $this->kreiraj_korisnika($vector);

        $vector['data']['id']=$row->ID_K;

        $this->kreiraj_izvodjaca($vector);
        $this->unsetAll();
        $this->moj_nalog();
    }

    public function registruj_organizator()
    {
        $vector = $this->proveri_podatke();
        if($vector['flag']==true)
        {
            $this->prikaz('registracija_organizator',$vector['data']);
            $this->unsetAll($vector);
            return;
        }
        $row = $this->kreiraj_korisnika($vector);

        $vector['data']['id']=$row->ID_K;

        $this->kreiraj_organizatora($vector);

        $this->unsetAll($vector);
        $this->moj_nalog();
    }

    public function registruj_posetilac()
    {
        $vector = $this->proveri_podatke_posetilac();
        if($vector['flag']==true)
        {
            $this->prikaz('registracija_posetilac',$vector['data']);
            $this->unsetAll($vector);
            return;
        }
        $row = $this->kreiraj_korisnika($vector);

        $vector['data']['id']=$row->ID_K;

        $this->kreiraj_posetioca($vector);

        $this->unsetAll($vector);
        $this->moj_nalog();
    }

    private function unsetAll($vector)
    {
        unset($vector);
    }

    public function loginSubmit()
    {
        $data = [];
        $data['usr']="is-valid";
        $data['password']='is-valid';
        $data['user_val']=$this->request->getPost('user');

        if(!($this->validate(['user'=>'required', 'password'=>'required'])))
        {
            $data['usr']="is-invalid";
            $data['password']='is-invalid';
            $data['user_msg']="Niste uneli Korisinicko ime, ili sifru!";
            return $this->prikaz('moj_nalog',$data);
        }

        $pattern="Email";
        if(strpos($this->request->getPost('user'),'@')===false)
        {
            $pattern="Korisnicko_Ime";
        }
        
        $korModel = new Korisnik();

        $row = $korModel->where($pattern,$this->request->getPost('user'))->first();

        if(($row)===null)
        {
            $data['usr']="is-invalid";
            $data['user_val']="";
            $data['user_msg']="Ne postoji zadati korisnik u nasoj aplikaciji!";
            return $this->prikaz('moj_nalog',$data);
        }
        $pw = $this->request->getPost('password');

        if(($row->Sifra) !== ($pw))
        {
            $data['password']='is-invalid';
            $data['password_msg']="uneli ste pogresnu lozinku!";
            return $this->prikaz('moj_nalog',$data);
        }
        $this->session->set('korisnik',$row);

        $posModel = new Posetilac();
        $user = $posModel->find($row->ID_K);
        
        if($user!==null)
        {
            $this->session->set('PosetilacController',$user);
            $this->session->set('tip','PosetilacController');
            return redirect()->to(site_url('PosetilacController'));
        }

        $orgModel = new Organizator();
        $user = $orgModel->find($row->ID_K);
        
        if($user!==null)
        {
            $this->session->set('OrganizatorController',$user);
            $this->session->set('tip','OrganizatorController');
            return redirect()->to(site_url('OrganizatorController'));
        }

        $izvModel = new Izvodjac();

        $user = $izvModel->find($row->ID_K);

        $this->session->set('IzvodjacController',$user);
        $this->session->set('tip','IzvodjacController');

        return redirect()->to(site_url('IzvodjacController'));
    }
}