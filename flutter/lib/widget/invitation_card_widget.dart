import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/session.dart';
import 'package:provider/provider.dart';
import '../class/event.dart';
import '../provider/session_provider.dart';

class InvitationCard extends StatefulWidget {
  const InvitationCard({super.key, required this.invitation});

  final Event invitation;

  @override
  State<InvitationCard> createState() => _InvitationCardState();
}

class _InvitationCardState extends State<InvitationCard> {
  bool formPending = false;
  String errorMessage = '';
  late Event invitation;
  bool _responded = false;

  @override
  void initState() {
    super.initState();
    invitation = widget.invitation;
  }

  Future<void> _acceptInvitation(context) async {
    setState(() {
      formPending = true;
      errorMessage = '';
    });

    Map bodyHttp = {
      'state': 'accepted',
      'comment': '',
    };

    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;
      dynamic responseHttp = await http.put(
        Uri.parse(
            '${dotenv.env['API_URL']}/events/${invitation.idEvent}/users/${user.id}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (responseHttp.body.isEmpty) {
        setState(() {
          errorMessage =
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
          formPending = false;
        });

        return;
      }

      Map<String, dynamic> response = jsonDecode(responseHttp.body);

      if (response.containsKey('code')) {
        setState(() {
          errorMessage = utf8.decode(response['message'].codeUnits);
          formPending = false;
        });

        return;
      }

      if (responseHttp.statusCode == 200) {
        setState(() {
          errorMessage = 'Invitation acceptée';
          formPending = false;
          _responded = true;
        });
      } else {
        setState(() {
          errorMessage =
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
          formPending = false;
        });
      }
    } catch (error) {
      setState(() {
        errorMessage =
            'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
        formPending = false;
      });
    }
  }

  Future<void> _refuseInvitation(context) async {
    setState(() {
      formPending = true;
      errorMessage = '';
    });

    Map bodyHttp = {
      'state': 'refused',
      'comment': '',
    };

    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;
      dynamic responseHttp = await http.put(
        Uri.parse(
            '${dotenv.env['API_URL']}/events/${invitation.idEvent}/users/${user.id}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (responseHttp.body.isEmpty) {
        setState(() {
          errorMessage =
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
          formPending = false;
        });

        return;
      }

      Map<String, dynamic> response = jsonDecode(responseHttp.body);

      if (response.containsKey('code')) {
        setState(() {
          errorMessage = utf8.decode(response['message'].codeUnits);
          formPending = false;
        });

        return;
      }

      if (responseHttp.statusCode == 200) {
        setState(() {
          errorMessage = 'Invitation acceptée';
          formPending = false;
          _responded = true;
        });
      } else {
        setState(() {
          errorMessage =
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
          formPending = false;
        });
      }
    } catch (error) {
      setState(() {
        errorMessage =
            'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
        formPending = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Visibility(
      visible: !_responded,
      child: Card(
        elevation: 8,
        child: Column(
          children: [
            ListTile(
              title: Text(invitation.title),
              subtitle: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                      '${DateFormat('dd/MM/yyyy').format(invitation.date)} à ${invitation.hour.hour.toString().padLeft(2, '0')}:${invitation.hour.minute.toString().padLeft(2, '0')}'),
                ],
              ),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  IconButton(
                    tooltip: "Accepter l'invitation",
                    color: Colors.green,
                    onPressed: () => _acceptInvitation(context),
                    icon: const Icon(Icons.check),
                  ),
                  IconButton(
                    tooltip: "Refuser l'invitation",
                    color: Colors.red,
                    onPressed: () => _refuseInvitation(context),
                    icon: const Icon(Icons.close),
                  ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
