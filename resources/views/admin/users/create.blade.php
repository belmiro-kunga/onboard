+<x-layouts.admin title="Criar Usuário">
    <div class="min-h-screen bg-gradie900">
        <!-- Header Secn -->
        <div class="relative overflow-hidden 2xl">
            <div class="absolute insetiv>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-
                    ">
                  >
                                <path stroke-linecap="round"-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Criar Novo Usuário</h1>
                          >
                        </div>
                    
                  -x-4">
              2">
>
                     
                          vg>
                            <span>Voltar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Progress Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 </div>
                        <span class="ml-2 text-blue-400 font-medium">Informações Básicas</span>
                    </div>
                    <div class="w-16 h-1 bg-slfull">
                        <div class="w-full h-1 bg-blue-500 rounded-full"></div>
                    </div>
                    <div class="fler">
div>
                        <span class="ml-2 /span>
                    </div>
                    <div class="w-16 h-1 bg-slate-700 rounded-full">
                        <div class="w-full h
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">3</div>
                        <span class="ml-2 text-
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <form id="createUserF>
                @csrf
                
                <!-- Step 1: Basic Inforn -->
                <div class="form-step active" data-step="1">
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
                        <div class="bg-gradient-to
                            <div class="flex items-center space-x-3">
                                <div clasl">
                                  >
"/>
                                    </svg>
                                <
                                <div>
                                    <h3 class="text-x
                                    <p c
                                </div>
                            </div>
                        </div>
                        
                        <d">

                                <!-- Name -->
                         up">
                                    <label for="name" class="block text-sm font-semibold text-white mb-2">
                                        Nome Completo *
                                    </label>
                                 >
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            "
                                        eto">
                                        <div class="absolute inset-y-0">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" strok/>
                                            </svg>
                                        </div>
                                    </div>
                                    @errome')
                                        <p clater">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewB">
                                         "/>
                                  
ssage }}
                                        </p>
                                 
                                </div>

                                <!-- Ema -->
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-semibold text-white 2">
                                        E-mail Corporativo *
                                    </label>
                                    <div class="relative">
                                        <input type="equired
                                            
                                      m">
                                  -3">
                              ">
                          />
  </svg>
                                        </div>
                         >
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-400r">
                                            <sv24">
                                 >
                                            </svg>
                                            {{ $}
                                        >
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div clas">
                                  e mb-2">
one
                                    </label>
                                 tive">
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                         "
                                        
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <p
                                            </svg>
                                        <
                                  v>
e')
                                        <pcenter">
                                 4">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            svg>
                                        
                                        </p>
                                    @enderror
                                </div>

                                <!-- Depa->
                                <d
                              
                          
el>
                                    <div >
                                        <select name="department" id="department"
                                                class="w-full bg-sln-300">
                                            <option value="">Selecione o departamento</option>
                                    >
                            
                                            <oion>
                                            <option value="Marketing" {{ old('department') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                            <option value="Financeiro" {{ old('department') === 'Financeiro'tion>
                                            <option value="Operações" {{ old('department') === 'Operações' ? 'selected' : '' }}>Operações</on>
                                  
                                        <">
                                 ">
                          "/>
                       /svg>
                  iv>
              iv>
          t')
                  n>outs.admi
</x-laycript>);
    </sadFormData( // lo    
   age loaddata on poad saved        // L;

 
        })   }, 1000);       Data');
  serFormem('createUoveItemlStorage.r  loca   
           out(() => {   setTime    {
     unction() ', fmit('subtenerdEventLisForm').adteUsertById('creagetElemendocument.     ion
    submissccessfulta on suaved dalear s      // CData);

  Form, savenput'tListener('i.addEvenUserForm')eateId('crmentByElet.getmen docu     put
   data on inave form     // S }

   }
           );
             }              }
                      }
                   
data[key];.value =       field                  
    lse {      } e                 = '1';
 ey] === data[kd keecld.ch       fie                x') {
     ckbochepe === ' (field.ty         if            
   d) {    if (fiel             "]`);
   me="${key}elector(`[naument.querySield = docst f        con         > {
   ch(key =.forEakeys(data)ct.bje    O       
                   
  avedData);JSON.parse(sdata =     const            
 {avedData) (s       if 
     ata');erFormDcreateUstem('etItorage.ga = localSedDatonst sav         c  () {
 ormDataion loadF      funct
    }
   a));
   ingify(datSON.strmData', JserForeateUtItem('cre.secalStorag    lo    
                      }
           }
     e;
      key] = valu data[                
   rmation') {d_confi= 'passwor != key' &&sword 'paskey !==   if (            ()) {
 ntriesrmData.e of foey, value]let [kr (       fo   
     
         {}; = t datans  co
          Form'));ateUserId('cregetElementByent.ocumFormData(da = new  formDat       const  {
   ata() aveFormD function s      
 lStorageo loca data to-save formAut       // 

       }  e', type);
typttribute('  field.setA      ;
     'password' 'text' :ord' ?= 'passw') ==ibute('typettretA = field.g  const type          fieldId);
lementById(getE= document.ield onst f           c
 dId) {sword(fielogglePastion t     func}

     
       });        ;
    = value.value  this              
       
               }         ');
 $1) $2'(, )/{0,5}(\d2})\d{e(/(ue.replace = val valu            
       >= 3) {alue.length  if (velse     }          3');
  ) $2-$/, '($1(\d{0,4})\d{4})})(ace(/(\d{2e.repllualue = va  v          
         >= 7) {.lengthlue if (va else          }      $2-$3');
) d{4})/, '($1(\d{5})(\\d{2})eplace(/(value.rlue =  va               
     { >= 11)lue.length (va          if  
                  ');
  D/g, 'place(/\value.ree = this.alu    let v          on() {
  put', functi'inListener(t.addEventphoneInpu                   
    );
 d('phone'etElementByIment.gocueInput = don    const ph    
    k() {ePhoneMastializtion inifunc
                }
     });
 }
               
       e-400';ext-slat t2 text-xs 'mt-ssName =la.conescriptioleD r               
     {} else               ow-400';
 text-yellxt-xs 2 teName = 'mt-iption.class   roleDescr               {
   anager')=== 'mif (role      } else            ;
0'd-40ext-res t'mt-2 text-xe = .classNamriptioneDescol           r     {
    n')  === 'admi if (role                 
            issões';
  er as perm v paranção uma fuioneec 'Sel] ||ons[roledescriptiContent = textription.Desc role             ;
  is.value role = thonst      c       ion() {
   , funct'change'ner(istet.addEventLleSelec        ro

     };           ões'
e configuraçsuários gestão de uo ndcluiintema, ao sisso completo : 'Acesin'dm      'a
          e',da equipos riatórelão de sualizaç + vicionárioesso de funger': 'Acnama '       ',
        ertificaçõesnto e cde treinameulos aos mód'Acesso ployee': 'em         
       tions = { descrip const              
  
       ription');esce-dById('roletElementt.gcumendotion = escript roleDns         corole');
   entById('tElemnt.ge = documeoleSelect     const r
       ) {riptions(eRoleDescn initializnctio       fu }

        
00'); 'text-red-4ow-400' : 'text-yellth >= 2 ?00' : strengxt-green-4 ? 'teength >= 3trdium ' + (st-meonassName = 'f.cl text       a';
    'Muito fracth - 1] || engls[str= levet onten  text.textC
                      ;
   })
         te-600');sla- 1] : 'bg-[strength  ? colorsgthdex < stren (in rounded ' +flex-1e = 'h-1 Nam bar.class       
        ex) => {indch((bar, Eabars.for              
     ;
     reen-600'], 'bg-gg-green-500'w-500', 'b', 'bg-yelloge-500bg-oran', '['bg-red-500 = lorsconst co         '];
   rte', 'For', 'BoaRegulaa', ', 'Fracto fraca'els = ['Mui   const lev        ext) {
  ts,argth, btrenator(strengthIndiction updateS  func     

       }
  re;eturn sco       r            
;
      score++ord)).test(passwz0-9]/^A-Za-if (/[           +;
 ord)) score+(passw/[0-9]/.test if (       ++;
    ) scoressword)Z]/.test(pa[A-  if (/    ;
      ++coresword)) s]/.test(pasa-z/[   if (
         8) score++;h >= d.lengtor if (passw         
       0;
       =   let score        {
   h(password) StrengtsswordalculatePan c     functio   }

              });

                };
      'hidden')d(ssList.ador.clandicatthI      streng            {
         } else          hText);
rengtars, sthBengtgth, strr(strenngthIndicatoupdateStre               ord);
     passwtrength(PasswordSteth = calculaconst streng                   
               );
      n'idderemove('hlassList.or.cdicatstrengthIn           
         h > 0) {lengtord.  if (passw               
              value;
 d = this.sswor const pa         {
      ion() functer('input', istenut.addEventLordInp passw
           
l('.h-1');rySelectorAlicator.quehInd strengtengthBars = strnst  co
          xt');rength-tentById('stt.getElemedocumenengthText = nst strco           gth');
 renssword-st('pamentByIdleetEument.gicator = docengthInd  const str        
  sword');asd('pyIntB.getElemecumentdout = dInpswor pasconst           
 rength() {ordStePasswn initializio       funct
 
    }    Match);
ordssw, checkPaut''inptener(is.addEventLconfirmInput          Match);
  swordt', checkPaser('inputenntLisdEverdInput.ad   passwo        }

        }
                
     -500');ed('border-rmoveist.resLclasfirmInput.       con          
   n');'hiddedd(classList.aator.  matchIndic        
          lse {     } e       ;
    -red-500')'borderist.add(classLfirmInput.    con               en');
 ('hiddmove.reListicator.classInd   match               {
   nput.value)= confirmIue !=ordInput.valswasvalue && pput.f (confirmIn  i        {
       h()MatcheckPasswordn c    functio
        tch');
word-mantById('passgetElemeocument. ddicator =matchInst    con
         );n'irmatioconfrd_passwo('tElementByIdnt.geput = documeIn confirmonst    c
        assword');Id('pmentByment.getEle= docuswordInput t pas       cons     lidation
ation vad confirm/ Passwor          /
  
      });
               }
       0');ate-60r-sl('bordelassList.addthis.c              0');
      red-50rder-e('bomovt.reclassLis       this.            {
  else  }           
    ');er-slate-600rdve('bot.remosLisis.clas      th            0');
  r-red-50dd('bordeassList.a     this.cl           ) {
    est(email)x.tailRege && !em (emailif           
                 ]+$/;
    .[^\s@^\s@]+\\s@]+@[= /^[^ emailRegex ston    c            lue;
.va thisonst email =       c{
         tion() funcer('input', ventListenddEt.apu emailIn      ');
     ('emailmentByIdleocument.getEt = dnpu emailI       const     ion
mail validat emel-ti   // Rea  
       
         });  idden');
 t.remove('h.classLisingadmitLo      sub        one';
  display = 'n.style.mitText         sub
        = true;Btn.disabledsubmit         
       nction(e) {ubmit', futListener('sEven   form.add        g');

 oadinbmitLById('sutElementdocument.geding = ubmitLoaonst s          c');
  exttT'submiById(getElement = document.t submitText        cons    tBtn');
miubentById('sment.getElemocu= dsubmitBtn   const         m');
  erForeUsId('createntBy.getElementcum= doonst form       c
      n() {idatioFormVallizen initia   functio  

          });();
 izePhoneMask   initial      ns();
   escriptioeDRolinitialize          h();
  ngtdStreworalizePassniti           i();
 ontialidaFormVize  initial     tions
     teracnd inidation a val/ Form        /() {
    on functitentLoaded',MConer('DOtenddEventLis  document.a   
     <script>style>

      </#059669; }
ound-color: kgrrong { bach-stword-strengt   .pass }
     10b981;color: #ckground-ood { bath-greng.password-st       59e0b; }
 or: #found-colckgrh-fair { ba-strengtsword .pas
       4; }: #ef444olorround-c backgak {trength-wesword-s     .pas    
 
           } 0.15);
    130, 246,gba(59,x rx 25pw: 0 10px-shado        box);
    slateY(-1porm: transf   tran     
    us {select:focrm-group    .focus,
     p input:fo-grou .form   
         }
     
      }      );
      ranslateY(0transform: t             1;
   ty:  opaci            to {
                 }
    x);
      0peY(2 translatform: trans       ;
        : 0   opacity          om {
            frInUp {
   adekeyframes f        @   
 }
     ;
       ase-out 0.5s edeInUpnimation: fa  a      k;
    oc display: bl     ve {
      step.acti      .form-     
   }
   ;
       y: nonepla  dis
          rm-step {fo    .    style>


    < </div>iv>
          </d</form>
            </div>
            ton>
     /but       <            >
      </div                   "></div>
hiterder-wer-b-2 bo-4 bord h-4 wunded-fullmate-spin rolass="ani      <div c             >
         -2"n mls="hiddeading" clastLod="submi     <div i                   rio</span>
uát">Criar UsubmitTex"sd=n i      <spa              svg>
     </                >
       H3v-1z"/ 0 0112 0v10zM3 20a6 6 0 018 -8 0 4 4112-5a4 4 0 -3m-0-3h3m-3 0h3m0 0v3m="M18 9v" dth="2oke-wid" strin="roundtroke-linejod" s"rounke-linecap=  <path stro                        4">
   2Box="0 0 24r" viewolocurrentCoke="strone" " fill="nr-25 m"w-5 h-g class=       <sv            
     -500/25">bluer:shadow-ow-lg hoveale-105 shad hover:scation-300ion-all durbold transit-semifontded-xl white rounext-ue-700 tto-blover:blue-600 h hover:from--blue-600-blue-500 to from-to-rntbg-gradie3  px-8 py-center-flex items-ss="inline       cla                   
  "submitBtnmit" id="ype="subon t <butt                 
                >
            </a            lar
    Cance                
      </svg>                   
     12"/>18 6M6 6l12 8L" d="M6 1h="2troke-widtound" slinejoin="r stroke-ap="round"troke-linec s<path                      >
      24"4 0 0 2"viewBox=rentColor" "curstroke=ne" ll="no2" fi5 h-5 mr-class="w- <svg                     -105">
   er:scaleion-300 hov-all duratansitionedium trxl font-m50 rounded--700/-slatebg0/50 hover: bg-slate-80late-3000 text-sslate-60r-r borderde-6 py-3 boms-center pxflex iteline-lass="in c               
        "}}index') ers.in.usroute('admf="{{  hre       <a         
     pt-8">ms-centeren itewebettify-flex juss="v clas     <di       ->
    ons -ActiForm   <!--               div>

     </      >
     /div           <  v>
            </di             >
            </div                         </div>
                            >
   </div                                 div>
   </                                 iv>
              </d                        
           </label>                                            >
        </div                                         
       </span>vindasoas-Email de Bnviar >Emedium"white font-lass="text-   <span c                                                     </svg>
                                                 "/>
       1-4.5 1.207 0959 8.959 0206a8.9 9m4.5-1. 10-5 0V12a9 9 0.5 0 005a2.5 20 0v1. 008 0zm 0 4 4 0 10-8 4 0a4 12M16"2" d="width=e- stroknd""roue-linejoin=ound" stroklinecap="r stroke-path       <                                                 4">
     24 20 0Box=" viewor""currentColke= stro="none"e-400" fillh-4 text-bluass="w-4   <svg cl                                               
       ">r space-x-2items-centeex ="fllass    <div c                                                enter">
flex items-cs="ml-3 mail" claslcome_eend_webel for="s    <la                                    >
        late-700" bg-s00 roundedte-5rder-slalue-500 bos:ring-bocu500 f5 text-blue-s="h-5 w-clas                                                  ecked
     lue="1" chmail" vame_elcoid="send_weemail" me_welcome="send_box" nacheck"ut type=     <inp                                          00/50">
 ate-6slr-order bordexl bounded--700/30 rlateer p-3 bg-sx items-centass="fle     <div cl                                     v>
  </di                                       el>
          </lab                                          /div>
           <                                    n>
      iva</spa At>Conta-medium"fontxt-white class="te     <span                                                 
   iv>"></ded-full00 round bg-green-4ss="w-2 h-2iv cla        <d                                               x-2">
 ce-er spa items-centexv class="fldi          <                                         center">
 ex items-fl="ml-3 e" classiv"is_act for= <label                                            ">
   0te-70d bg-sla0 rounde50-slate-500 borderring-green-s:00 focun-5reew-5 text-g"h-5 lass=          c                                         
    ' }}hecked' : ', true) ? 'cve'is_acti old('         {{                                            "1" 
  value=e" d="is_activactive" i"is_" name=oxkbtype="chect   <inpu                                           
   ">e-600/50r-slater bordebordxl ded-700/30 roun3 bg-slate-s-center p-="flex itemlassdiv c        <                                 >
   ace-y-3" class="sp  <div                                     bel>
 la</                                     Conta
       Status da                                    2">
     hite mb-ld text-wnt-semibok text-sm fos="blocas cl  <label                                      >
m-group"s="foriv clas   <d                               -->
   !-- Status    <                                </div>

                                    r
roender     @                                   </p>
                                             }}
ge{ $messa       {                                        </svg>
                                           
      18 0z"/> 0 0111-18 0 9 9a9 9 0  4h.01M21 1212 8v4m0 d="Midth="2"roke-w"round" stjoin=roke-line" stroundnecap="stroke-li     <path                                       >
          0 24 24"ox="0lor" viewBntCoe="curreokne" str="nomr-1" fill="w-4 h-4 vg class        <s                                        
enter">flex items-cd-400 sm text-rext-te2 ="mt- <p class                                          )
 role'or('rr      @e                              div>
           </                           ões
      ssver as permira ão pa funçumacione  Sele                                
           ">-slate-400-xs text2 text="mt-" classionript"role-desc  <div id=                                      
      </div>                                div>
  </                                             </svg>
                                              
  7-7-7"/>9 9l-7 d="M1width="2"ke- stro"round"oin=nej" stroke-lioundcap="re-linerok stath        <p                                         ">
    0 24 24ewBox="0or" virrentCole="cu" strokill="none f400"text-slate-5 ="w-5 h-ss<svg cla                                               -none">
 r-eventsointer pr-3 p-cente0 flex itemst-set-y-0 righinolute class="abs <div                                     t>
       </selec                                        n>
    </optioadordministr🛡️ A : '' }}>lected' ? 'se=== 'admin''role') " {{ old(alue="adminption v      <o                                      
    te</option>Geren>👔  }}ected' : ''? 'selnager' ) === 'mald('role'er" {{ oag"manue= val  <option                                         >
     ário</optioncion Fun' }}>👤cted' : 'yee' ? 'seleloemp') === 'ld('role" {{ o="employeeon value <opti                                              >
 ão</optionone uma funç"">Selecilue= <option va                                             -300">
   durationn-alltransitiorent r-transpaus:bordeue-500 focring-bling-2 focus: focus:rxl px-4 py-3ed-roundt-white 00 texder-slate-6borer 700/50 bordlate- bg-sullss="w-f     cla                                         d
      e" require" id="rolme="roleelect na       <s                                 >
    "s="relativeasdiv cl       <                               label>
  </                                *
        Sistema ão no   Funç                                      2">
    ite mb-xt-whold teibm font-sem-s"block text= class"role"<label for=                                        group">
form-s="div clas     <                            ole -->
   - R!-    <                              gap-6">
  ls-2  lg:grid-co-1grid-cols"grid  class= <div                                    
                      </h4>
                                
     Statusunção &          F                        
   g>      </sv                   
           z"/>1H3v-12 0va6 6 0 011018 0zM3 20 4 4 0 -8 011 4 0 -5a4m-3-3h6m-2d="M12 15v3th="2" ke-widstrond" ouejoin="rin stroke-l"nd="rounecaproke-li  <path st                                   ">
   44 2"0 0 2 viewBox=ntColor""currestroke=" ="nonee-400" fillpurpl text--5 mr-2"w-5 hs=   <svg clas                                 ">
ems-centerx itb-4 flete mld text-whi font-semiboss="text-lg cla<h4                                ">
e-700/50order-slat b6 borderd-xl p-undete-800/50 ros="bg-sla   <div clas                      
   ction --> Status Se Role &       <!--          
           div>
         </                 </div>
                              div>
       </                              /div>
    <                                  
     ncidem</p>has não coi">As senext-red-400-xs ts="textclas         <p                                 
   en">-2 hidd"mtclass=d-match" sworiv id="pas <d                                     
  v>     </di                                   tton>
    </bu                             
               </svg>                                            "/>
3-9.542-7z2.940-8.268-77 7-4.4542 9. 7--5.0644.0571.274 .542 7- 2.943 98.268.478 0  5c4.523 5 1232 7.943 7.7"M2.458 12C32" d=-width="roke"round" stjoin=-linend" stroke="rouapnecstroke-lih         <pat                                        0z"/>
     016 0 3 3 03 0 11-6 15 12a3 2" d="Mth="roke-wid"round" stnejoin=" stroke-liundro"linecap=stroke-  <path                                              24">
     x="0 0 24  viewBoentColor""currstroke=" "nonell=lors" fin-coransitioite text-whover:te-400 htext-slat"w-5 h-5  class=vg   <s                                          ">
   tion')d_confirmad('passworswor="togglePasclick-3" on prcenteritems-ight-0 flex nset-y-0 rbsolute i="aton" classe="bututton typ<b                                          senha">
  pita a der="Re  placehol                                              400"
   te-r-slaholde300 placel duration-nsition-alent traranspars:border-tue-500 focu-blingocus:rring-2 focus:2 f-3 pr-1l px-4 pyd-xroundewhite te-600 text- border-slarderte-700/50 bo-sla"w-full bg   class=                                            uired
    ation" reqrd_confirm="passwoion" idmatirassword_confname="p" ="passwordut type      <inp                              
        ve">"relatiss=   <div cla                                  el>
      </lab                               
       *Senhairmar Conf                                     ">
       2te mb--whiemibold textxt-sm font-sblock te" class="ationfirmword_confor="passbel        <la                      
           roup">-g="formv class         <di                        ion -->
   d Confirmat Passwor--     <!                            >

        </div                        
         @enderror                                    </p>
                                      
        age }}    {{ $mess                                            </svg>
                                        
        0118 0z"/>0 18 0 9 9  0 11-1M21 12a9 9v4m0 4h.0" d="M12 8"2dth= stroke-wi"ndrou-linejoin=" stroke="round"ecap stroke-lin   <path                                                 4 24">
ox="0 0 2viewBr" "currentColoroke=" stone1" fill="n-4 mr-w-4 hclass="<svg                                          
       nter">x items-ce00 flem text-red-4ext-sss="mt-2 t  <p cla                                  )
        rd'swoasrror('p  @e                                     div>
           </                       
       >ca</span></prao ft">Muith-tex"strengt=pan ida: <s senh daForça00 mt-1">slate-4ext-xs t"text-=ss  <p cla                                        /div>
        <                                  v>
    /di"><undedrote-600 -1 bg-slaflexss="h-1 cladiv  <                                               >
"></div0 roundedslate-60flex-1 bg-1 "h- class=   <div                                     v>
        "></di600 roundedg-slate- flex-1 bh-1 class="iv          <d                              v>
        ed"></die-600 roundex-1 bg-slat-1 fls="hclasv       <di                                       >
   -1"x space-x class="fle        <div                                  
  ">-2 hiddenss="mt clad-strength"="passwor     <div id                           v>
             </di                             
      utton>     </b                                     </svg>
                                               
   -7z"/>43-9.542.90-8.268-2477  7-4. 7-9.542.057-5.064274 47-1.2.943 9.542 0 8.268 478 5c4. 5 12 943 7.523 7.322C3.72.458 1="2" d="Midthtroke-wnd" sejoin="rou" stroke-linp="round-lineca stroke    <path                                                0z"/>
  3 3 0 0160 11-6 0 12a3 3 "2" d="M15th=oke-widnd" strrouejoin="-lin strokeap="round"oke-linecth str       <pa                                       4">
      ="0 0 24 2wBox" viecurrentColor" stroke="ll="none" fiolorson-cte transitiext-whiver:thoslate-400 t-"w-5 h-5 texass=     <svg cl                                        ">
   password')ePassword('toggl=" onclick3"-center pr-temsght-0 flex iinset-y-0 rite olulass="absbutton" ctype="<button                                        es">
     ractero 8 ca="Mínimholderace  pl                                                -400"
 der-slateehol0 placon-30n-all duratiansitiot tr-transparenderfocus:borlue-500 s:ring-b:ring-2 focuus-12 foc4 py-3 prnded-xl px-routext-white 600 te-der-slader bor0/50 bor bg-slate-70="w-full   class                                             equired
   " rwordd="passpassword" i" name="d"passworut type=        <inp                                   lative">
 ass="recl    <div                            
         label>    </                         
             Senha *                                      
    hite mb-2">ext-wt-semibold t fontext-sm"block ass=ord" cl for="passw  <label                                    up">
  rm-gro class="fo<div                             -->
       d !-- Passwor           <                    -6">
      gap-cols-21 lg:grids--col"grid gridv class=  <di                            
                               
        </h4>                
           essode Acnciais       Crede                         vg>
             </s                           
 00-2 2v2"/>2 0 2-2H9a2 0 00-2 2 -2-2m2 2V9a 2 0 00-2a2 0 002 2m-2 0a2 2012 2m0 0 2 2v2m0 0a00-2 2-2H9a2 2 0 0-22 0 0V5a2 -2h6m0 0 0 012 7a2 2 00-2 2v2M7 02-2H9a2 200-2 2 0 2m2-2V5a2 2 0 00-2 12 2m-2-2a2 2 0 0 0a2 0 012 2m0"M15 7a2 " d=-width="2trokend" sin="rouinejoe-lrokround" stp="caroke-linepath st           <                   
          24 24"> 0 "0iewBox=Color" vrentoke="cur"none" str fill=00"w-4ext-yellomr-2 t-5 s="w-5 h   <svg clas                         
        ">erems-centmb-4 flex it-white extibold tsemfont--lg xtte"  <h4 class=                              700/50">
late-er border-s-xl p-6 bordnded-800/50 roulateass="bg-sv cl <di                      -->
     on tiecPassword S- <!-                          -y-6">
  "p-6 spacelass=<div c                    
                      v>
      di      </            
      iv> </d                    iv>
               </d                      iais</p>
  edencsso e cre acees duraçõ">Configlate-400xt-sass="te      <p cl                      >
        ssões</h3rminça & Pee">Seguraittext-whbold ont-"text-xl fh3 class=     <                              
  <div>                           </div>
                              
        </svg>                                 "/>
 -3.016z.382-2.052-33.12-622 0-1.049-6.03 9-11.2 336-1. 11.622 5.17.824 10.29 9 3 9c0 5.59112.02 0 0033.04A12.02  01-8.618 955 01.44a11.955 1 0112 2.9955 11.955 0016A11.4m5.618-4.l2 2 4-12"M9  d=-width="2"" strokedn="rouninejoitroke-l"round" secap=-linkeh stroat      <p                               4 24">
   0 0 2iewBox="lor" vtCoenrrcue="" strokl="none00" filxt-red-46 h-6 teass="w-svg cl       <                     
        xl">rounded--2 red-500/20 pass="bg-     <div cl                    ">
       e-x-3 spaccenter"flex items-= class    <div                >
        0 p-6"/5-slate-700orderder-b bor500/10 bge--oran-500/10 torom-red f-to-rbg-gradientdiv class="          <            en">
  ow-hidd0 overflslate-700/5der-orer b bordd-2xl-900 roundeate-800 to-sl-slatefromo-br -gradient-tlass="bg c      <div            "2">
   data-step=tive"-step acclass="formiv <d         
       s -->& Permissionrity : SecuStep 2--  <!          
        </div>
           iv>
           </d
           div>   </                  /div>
      <                         v>
 </di                              r
  @enderro                                       </p>
                                  
   }} $message          {{                                g>
        </sv                               />
       0z"118  00 9 9 08 2a9 9 0 11-1 4h.01M21 14m0="M12 8v"2" d-width=nd" strokejoin="rouoke-line" strp="roundoke-lineca str       <path                                         
24">"0 0 24 ewBox= vior"ntColurre"ce=trokone" sll="n4 mr-1" fih-"w-4  <svg class=                                     r">
      ntecetems-x ired-400 flesm text- text-mt-2ss=" cla<p                                   
     ate')re_d  @error('hi                           >
       </div                                   
      </div>                                 /svg>
          <                               z"/>
     0 002 22 2 2v12a2  00-a2 2 0-2-2H5 0 00002-2V7a2 2a2 2 0 M5 21h148h10m-9 7V3m8 4V38  d="M"2"-width=kerond" st"rou-linejoin=oked" strcap="rounlinestroke-  <path                                             
  0 0 24 24">"ewBox=r" viColo="current" stroke="nonee-400" fill text-slat-5w-5 hass="vg cl   <s                                     ">
    noner-events-pr-3 pointer  items-cente flexht-0et-y-0 riglute inslass="abso <div c                                    00">
    duration-3ition-allnsnsparent tras:border-trafocu-blue-500 ringg-2 focus:rincus:x-4 py-3 fol pded-xoun rxt-white00 teer-slate-6rd border boe-700/50bg-slat="w-full     class                                    
       }"m-d')) }date('Y-ate', ('hire_dldue="{{ oalte" v"hire_da id=e_date"e="hirdate" namt type=" <inpu                                ">
       vess="relati    <div cla                              </label>
                                  o
    açãa de Contrat       Dat                      
           -2">t-white mb texboldemi-sxt-sm font="block tee" classr="hire_dat  <label fo                           >
       roup"rm-gs="foas   <div cl                          te -->
   ire Da<!-- H                              
  </div>
                         ror
       er@end                                   >
  </p                                    ge }}
   sa    {{ $mes                               
              </svg>                             
          "/> 0118 0z0 9 9 09 0 11-18 1 12a9 m0 4h.01M2"M12 8v4 d=""2h=widtke-stro" oin="roundoke-linejstr="round" linecapth stroke-   <pa                                       ">
       24 24wBox="0 0r" vientColo="currekeone" stroll="n mr-1" fiss="w-4 h-4svg cla       <                                r">
     ms-centetelex ixt-red-400 f text-sm tes="mt-2   <p clas                                   
  on')ositi@error('p                                      </div>
                              v>
    </di                                  svg>
           </                                      
 12-2V6"/>0 01-2-2V8a2 2 2 2 0 08a 2H2 0 01-22  0 012 2v6a 0V6a2 20-2-2v2m82 2 0 000-2-2h-4a 6V4a2 2 0 9-1.745M16 0-6.22-.62-5c-3.183 0 0112 1931931 23.3. 13.255A2" d="M21th="2stroke-wid"round" e-linejoin=" strokroundecap="-linkeh stro   <pat                                           4">
   0 24 2iewBox="0lor" vrentCoroke="cur st""nonell=te-400" fih-5 text-sla-5  class="w     <svg                                       ">
enter pr-3tems-cght-0 flex iriy-0 e inset-oluts="abs   <div clas                                    >
 e"rentalista, Ge Anolvedor,"Ex: Desenvr=oldeehac    pl                                     "
      400e-r-slatlaceholderation-300 pll dunsition-arent trapaansrder-trcus:boe-500 foring-blug-2 focus:ocus:riny-3 f p-4ounded-xl pxtext-white re-600 r-slatrde bo00/50 borderbg-slate-7ull ass="w-f         cl                               
       ') }}"on'positiue="{{ old(ition" valid="poson" iti name="post"="textypeinput       <                              e">
    tivss="rela<div cla                                    label>
      </                             nção
 Fu    Cargo/                            >
        2"white mb-ld text-mibo font-sesmext-ss="block tition" clael for="pos       <lab                           group">
  ass="form-v cl       <di                  >
       on -- Positi     <!--                        >

   </div                                ror
@ender                                 </p>
                                      ge }}
     messa  {{ $                                  
        svg>       </                            
         z"/>18 00 9 9 0 019 0 11-18 2a9 21 18v4m0 4h.01Md="M12 dth="2"  stroke-wiund"inejoin="rotroke-l"round" ske-linecap=ath stro       <p                                     >
    4"x="0 0 24 2Bo viewColor"ente="currnone" strok fill=" h-4 mr-1""w-4ass=     <svg cl                                  >
     ms-center"0 flex iteed-40xt-rtext-sm tess="mt-2 la       <p c               