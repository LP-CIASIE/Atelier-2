import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import 'package:lp1_ciasie_atelier_2/class/session.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:provider/provider.dart';

class EventShareWidget extends StatefulWidget {
  final String idEvent;
  const EventShareWidget({
    super.key,
    required this.idEvent,
  });

  @override
  State<EventShareWidget> createState() => _EventShareWidgetState();
}

class _EventShareWidgetState extends State<EventShareWidget> {
  final TextEditingController _filterController = TextEditingController();
  String errorMessage = '';
  bool pending = false;
  List<User> users = [];

  Future<List<String>> getGuest() async {
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.get(
        Uri.parse('${dotenv.env['API_URL']}/events/${widget.idEvent}/users'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('error')) {
          if (response['error'] == 404) {
            throw CustomException(message: "Vos évènements sont introuvables");
          }
          if (response['error'] == 401) {
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à accéder à cette ressource.");
          }
          if (response.containsKey('message')) {
            throw CustomException(message: response['message']);
          }
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        }
        if (response.containsKey('usersEvent')) {
          List listMap = response['usersEvent'];

          List<String> listUser = [];

          for (var userEvent in listMap) {
            listUser.add(userEvent['id_user']);
          }

          return listUser;
        } else {
          throw CustomException(message: "Vous n'avez pas encore d'évènement.");
        }
      } else {
        throw CustomException(
            message:
                "Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.");
      }
    } catch (error) {
      if (error is! CustomException) {
        throw CustomException(
            message:
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.');
      }
      rethrow;
    }
  }

  void searchUsers(String stringSearch) async {
    setState(() {
      users.clear();
      errorMessage = '';
      pending = true;
    });
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.get(
        Uri.parse('${dotenv.env['API_URL']}/users?email=$stringSearch'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          if (response['code'] == 404) {
            setState(() {
              pending = false;
            });
            throw CustomException(
                message: 'Aucun utilisateur trouvé à cette adresse email.');
          }
          if (response['code'] == 401) {
            setState(() {
              pending = false;
            });
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à accéder à cette ressource.");
          }
          if (response.containsKey('message')) {
            setState(() {
              pending = false;
            });
            throw CustomException(message: response['message']);
          }
          setState(() {
            pending = false;
          });
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        }
        if (response.containsKey('users')) {
          List listMap = response['users'];

          List<User> listUser = [];
          for (var user in listMap) {
            listUser.add(User.fromMap(user!));
          }

          List<String> guests = await getGuest();

          for (var guest in guests) {
            for (int i = 0; i < listUser.length; i++) {
              if (listUser[i].id == guest) {
                listUser[i].isCheck = true;
              }
            }
          }

          setState(() {
            pending = false;
            users = listUser;
          });
        } else {
          setState(() {
            pending = false;
          });
          throw CustomException(
              message:
                  'Aucun utilisateur ne correspond à l\'adresse email renseignée.');
        }
      } else {
        setState(() {
          pending = false;
        });
        throw CustomException(
            message:
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.');
      }
    } catch (error) {
      if (error is! CustomException) {
        setState(() {
          pending = false;
          errorMessage =
              "Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.";
        });
      }
      setState(() {
        pending = false;
        errorMessage = error.toString();
      });
    }
  }

  void addGuest(int index) async {
    setState(() {
      users[index].isCheck = true;
    });
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.post(
        Uri.parse(
            '${dotenv.env['API_URL']}/events/${widget.idEvent}/users/${users[index].id}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          if (response['code'] == 404) {
            throw CustomException(
                message: "L'utilisateur ou l'évènement n'existe plus");
          }
          if (response['code'] == 401) {
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à partager à cet évènement.");
          }
          if (response.containsKey('message')) {
            throw CustomException(message: utf8.decode(response['message']));
          }
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        }
      }
    } catch (error) {
      if (error is! CustomException) {
        setState(() {
          users[index].isCheck = false;
        });
        SnackBar snackBar = const SnackBar(
          content: Text(
              "Un problème est survenu, veuillez vérifier votre connexion internet et réessayer."),
        );
        ScaffoldMessenger.of(context).showSnackBar(snackBar);
      }
      setState(() {
        users[index].isCheck = false;
      });
      SnackBar snackBar = SnackBar(
        content: Text(error.toString()),
      );
      ScaffoldMessenger.of(context).showSnackBar(snackBar);
    }
  }

  void deleteGuest(int index) async {
    setState(() {
      users[index].isCheck = false;
    });
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.delete(
        Uri.parse(
            '${dotenv.env['API_URL']}/events/${widget.idEvent}/users/${users[index].id}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          if (response['code'] == 404) {
            throw CustomException(
                message: "L'utilisateur ou l'évènement n'existe plus");
          }
          if (response['code'] == 401) {
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à partager à cet évènement.");
          }
          if (response.containsKey('message')) {
            throw CustomException(message: response['message']);
          }
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        }
      }
    } catch (error) {
      if (error is! CustomException) {
        setState(() {
          users[index].isCheck = true;
        });
      }
      setState(() {
        users[index].isCheck = true;
      });
    }
  }

  void updateGuest(int index, bool? value) {
    print(value);
    if (value!) {
      addGuest(index);
    } else {
      deleteGuest(index);
    }
  }

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: TextField(
        controller: _filterController,
        decoration: const InputDecoration(
          border: OutlineInputBorder(),
          labelText: 'Rechercher un ami',
        ),
        onChanged: (String value) => {
          searchUsers(value),
        },
      ),
      content: SizedBox(
        width: double.maxFinite,
        child: pending
            ? Row(
                mainAxisAlignment: MainAxisAlignment.center,
                // mainAxisAlignment: MainAxisAlignment.center,
                children: const [CircularProgressIndicator()],
              )
            : errorMessage != ''
                ? Text(errorMessage)
                : ListView.builder(
                    shrinkWrap: true,
                    itemCount: users.length,
                    itemBuilder: (BuildContext context, int index) {
                      return CheckboxListTile(
                        value: users[index].isCheck,
                        onChanged: (bool? value) => {updateGuest(index, value)},
                        title: Text(users[index].email),
                      );
                    },
                  ),
      ),
    );
  }
}
