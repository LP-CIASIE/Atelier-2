import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_add_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/profil/profil_builder_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_in_screen.dart';
import 'package:provider/provider.dart';
import '../../widget/event_card_widget.dart';

class EventsPage extends StatefulWidget {
  const EventsPage({super.key});

  @override
  State<EventsPage> createState() => _EventsPageState();
}

class _EventsPageState extends State<EventsPage> {
  Future<List<Event>> futureEvents = Future.value([]);
  late List<Event> events;
  bool _pending = false;

  void refresh(context) async {
    setState(() {
      _pending = true;
    });
    List<Event> data = await fetchEvents(context);
    setState(() {
      events = data;
      _pending = false;
    });
  }

  Future<List<Event>> fetchEvents(context) async {
    try {
      User user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;
      dynamic responseHttp = await http.get(
        Uri.parse(
            '${dotenv.env['API_URL']}/events?page=1&size=1500&filter=accepted'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );

      if (user.accessToken == "") {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const SignInPage()),
        );
      }
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
        } else if (response.containsKey('events')) {
          List list = response['events'];

          List<Event> events = [];

          for (var event in list) {
            events.add(Event.fromMap(event));
          }

          return events;
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: const Text('Evènements'),
          automaticallyImplyLeading: false,
          actions: [
            IconButton(
                onPressed: () => refresh(context),
                icon: const Icon(Icons.refresh)),
            IconButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => const ProfilBuilderPage()),
                );
              },
              icon: const Icon(Icons.account_circle_outlined),
            ),
          ],
        ),
        body: Column(
          children: [
            Visibility(
                visible: _pending, child: const LinearProgressIndicator()),
            FutureBuilder<List<Event>>(
              future: fetchEvents(context),
              builder: (context, snapshot) {
                if (snapshot.hasData) {
                  return Expanded(
                    child: ListView.builder(
                      itemCount: snapshot.data!.length,
                      itemBuilder: (context, index) {
                        return EventCard(
                          event: snapshot.data![index],
                        );
                      },
                    ),
                  );
                } else if (snapshot.hasError) {
                  return Center(
                    child: Text("${snapshot.error}"),
                  );
                }
                return Container(
                  alignment: Alignment.center,
                  child: const CircularProgressIndicator(),
                );
              },
            ),
          ],
        ),
        floatingActionButton: FloatingActionButton(
          tooltip: "Créer un évènement",
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const EventAddPage()),
            );
          },
          child: const Icon(Icons.add_outlined),
        ));
  }
}
