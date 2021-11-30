unit Start;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, jpeg, ExtCtrls, Buttons, StdCtrls;

type
  TMain = class(TForm)
    Back: TImage;
    Server: TComboBox;
    Enter: TSpeedButton;
    Quit: TSpeedButton;
    AddNew: TSpeedButton;
    Kick: TSpeedButton;
    Login: TEdit;
    Password: TEdit;
    procedure LoadServers;
    procedure SaveServers;
    procedure QuitClick(Sender: TObject);
    procedure FormCreate(Sender: TObject);
    procedure EnterClick(Sender: TObject);
    procedure AddNewClick(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure KickClick(Sender: TObject);
  private
    { Private declarations }
  public
  Servers : array[0..100, 0..1] of String;
  Num : integer;
  end;

var
  Main: TMain;

implementation

{$R *.dfm}

procedure TMain.QuitClick(Sender: TObject);
begin
If (MessageDlg('¬ы уверены, что хотите выйти', mtConfirmation, [mbYes, mbNo], 0) = mrYes) then
  halt;
end;

procedure TMain.LoadServers;
var
f   : textfile;
begin
num := 0;
Server.Clear;
assignfile(f, 'Servers.ini');
reset(f);
while not eof(f) do
  begin
  readln(f, Servers[num, 0]);
  readln(f, Servers[num, 1]);
  Server.Items.Add(Servers[num, 1]);
  num := num + 1;
  end;
closefile(f);
Server.ItemIndex := 0;
end;

procedure TMain.FormCreate(Sender: TObject);
begin
LoadServers();
end;

procedure TMain.EnterClick(Sender: TObject);
Var
Cmd : String;
begin
if (length(Login.Text) > 0) and (length(Password.Text) > 0) Then
  Begin
  Cmd := 'game.exe -server ' + Servers[Server.ItemIndex, 0] + ' -login ' + Login.Text + ' -pass ' + Password.Text;
  MessageDlg(Cmd, mtInformation, [mbOk], 0);
  WinExec(PChar(Cmd), sw_ShowNormal);
  End;
end;

procedure TMain.AddNewClick(Sender: TObject);
var
  ServerName : string;
  ServerHost : string;
begin
  ServerName := InputBox('¬ведите им€ сервера (оно будет отображено в списке серверов)', 'ƒобавление нового сервера', '');
  ServerHost := InputBox('¬ведите адрес сервера', 'ƒобавление нового сервера', '');
  if (Length(ServerName) > 0) and (Length(ServerHost) > 0) then
    begin
    Servers[Num, 0] := ServerHost;
    Servers[Num, 1] := ServerName;
    Num := Num + 1;
    SaveServers();
    LoadServers();
    end;
end;

procedure TMain.SaveServers;
var
  f : textfile;
  i : integer;
begin
assignfile(f, 'Servers.ini');
rewrite(f);
for i := 0 to Num-1 Do
  begin
  if (Servers[i, 0] <> '') and (Servers[i, 1] <> '') then
    begin
    WriteLn(f, Servers[i, 0]);
    WriteLn(f, Servers[i, 1]);
    end;
  end;
closefile(f);
end;

procedure TMain.FormDestroy(Sender: TObject);
begin
SaveServers();
end;

procedure TMain.KickClick(Sender: TObject);
begin
if (Server.ItemIndex <> -1) then
  begin
  If (MessageDlg('¬ы уверены, что хотите удалить сервер "' + Server.Items.Strings[Server.ItemIndex] + '" из списка серверов', mtConfirmation, [mbYes, mbNo], 0) = mrYes) then
    begin
    Servers[Server.ItemIndex, 0] := '';
    Servers[Server.ItemIndex, 1] := '';
    SaveServers();
    LoadServers();
    end;
  end;
end;

end.
