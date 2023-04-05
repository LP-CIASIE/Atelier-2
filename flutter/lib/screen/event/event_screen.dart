import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/class/location.dart';
import 'package:lp1_ciasie_atelier_2/class/session.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_builder_screen.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';
import 'package:lp1_ciasie_atelier_2/widget/event_share_widget.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';
import 'package:lp1_ciasie_atelier_2/widget/event_share_widget.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';
import 'package:lp1_ciasie_atelier_2/widget/event_share_widget.dart';

class EventPage extends StatefulWidget {
  final Event event;
  const EventPage({
    super.key,
    super.key,
    required this.event,
  });
  });
  @override
  State<EventPage> createState() => _EventPageState();
}

class _EventPageState extends State<EventPage> {
  @override
  void initState() {
    super.initState();
  }

  _openDialogShareEvent() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return EventShareWidget(
          idEvent: widget.event.idEvent,
        );
      },
    );
  }

  _openDialogShareEvent() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return EventShareWidget(
          idEvent: widget.event.idEvent,
        );
      },
    );
  }

  Future<Location> fetchEventLocation(context) async {
    try {
      Session user =
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.get(
        Uri.parse(
            'https://api.tedyspo.cyprien-cotinaut.com/events/${widget.event.idEvent}/locations/'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );
      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('error')) {
          if (response['error'] == 404) {
            throw CustomException(message: "La location est introuvables");
          }
          if (response['error'] == 401) {
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à accéder à la ressource location.");
          }
          if (response.containsKey('message')) {
            throw CustomException(message: response['message']);
          }
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        } else if (response.containsKey('locations')) {
          try {
            Map map = response['locations'][0];
            Location location = Location.fromMap(map);
            return location;
          } catch (e) {
            throw CustomException(message: "Aucun lieu de rendez-vous ajouté.");
          }
          try {
            Map map = response['locations'][0];
            Location location = Location.fromMap(map);
            return location;
          } catch (e) {
            throw CustomException(message: "Aucun lieu de rendez-vous ajouté.");
          }
        } else {
          throw CustomException(message: "Vous n'avez pas encore de location.");
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

  Future<List<User>> fetchEventParticipent(context) async {

  Future<List<User>> fetchEventParticipent(context) async {
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.get(
        Uri.parse(
            'https://api.tedyspo.cyprien-cotinaut.com/events/${widget.event.idEvent}/users?embed=user'),
            'https://api.tedyspo.cyprien-cotinaut.com/events/${widget.event.idEvent}/users?embed=user'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
      );

      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('error')) {
          if (response['error'] == 404) {
            throw CustomException(message: "La participant est introuvables");
          }
          if (response['error'] == 401) {
            throw CustomException(
                message:
                    "Vous n'êtes pas autorisé à accéder à la ressource users.");
          }
          if (response.containsKey('message')) {
            throw CustomException(message: response['message']);
          }
          throw CustomException(
              message: "Une erreur est survenue : ${response['code']}.");
        } else if (response.containsKey('usersEvent')) {
          List list = response['usersEvent'];
          List<User> participants = [];

          for (var participant in list) {
            participants.add(User.fromMap(participant));
          }

          return participants;
        } else {
          throw CustomException(message: "Vous n'avez pas de participant.");
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
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_outlined),
          icon: const Icon(Icons.arrow_back_outlined),
          onPressed: () => {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => const HomePage(),
              ),
            )
          },
        ),
        actions: [
          Visibility(
            visible: widget.event.iAmOwner,
            child: IconButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => EventEditBuilderPage(
                      idEvent: widget.event.idEvent,
                    ),
                  ),
                );
          Visibility(
            visible: widget.event.iAmOwner,
            child: IconButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => EventEditBuilderPage(
                      idEvent: widget.event.idEvent,
                    ),
                  ),
                );
              },
              icon: const Icon(Icons.edit_outlined),
            ),
          ),
          Visibility(
            visible: widget.event.iAmOwner,
            child: IconButton(
          ),
          Visibility(
            visible: widget.event.iAmOwner,
            child: IconButton(
              onPressed: () {},
              icon: const Icon(Icons.delete_outlined),
            ),
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: Text(widget.event.title,
                    style: const TextStyle(fontSize: 24.6)),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: Text(
                    '${DateFormat('dd/MM/yyyy').format(widget.event.date)} à ${widget.event.hour.hour.toString().padLeft(2, '0')}:${widget.event.hour.minute.toString().padLeft(2, '0')}',
                    style: const TextStyle(fontSize: 19.6)),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: Text(
                  widget.event.description,
                ),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 16),
                child: FutureBuilder(
                  future: fetchEventLocation(context),
                  builder: (context, snapshot) {
                    if (snapshot.hasError) {
                      return Text(snapshot.error.toString());
                    } else if (snapshot.hasData) {
                      return SizedBox(
                        height: 200,
                        width: 200,
                        child: FlutterMap(
                          options: MapOptions(
                            center: LatLng(
                              snapshot.data!.lat,
                              snapshot.data!.long,
                            ),
                            zoom: 13.0,
                          ),
                          nonRotatedChildren: [
                            AttributionWidget.defaultWidget(
                              source: 'OpenStreetMap contributors',
                              onSourceTapped: null,
                            ),
                          ],
                          children: [
                            TileLayer(
                              urlTemplate:
                                  'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                              userAgentPackageName: 'com.example.app',
                            ),
                            MarkerLayer(
                              markers: [
                                Marker(
                                  width: 80.0,
                                  height: 80.0,
                                  point: LatLng(
                                      snapshot.data!.lat, snapshot.data!.long),
                                  builder: (ctx) => Container(
                                    child: const Icon(Icons.location_on),
                                  ),
                                ),
                              ],
                            )
                          ],
                        ),
                      );
                    } else {
                      return const CircularProgressIndicator();
                    }
                  },
                ),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Participants',
                      style: TextStyle(fontSize: 19.6),
                    ),
                    Visibility(
                      visible: widget.event.iAmOwner,
                      child: OutlinedButton.icon(
                        onPressed: () => {_openDialogShareEvent()},
                        icon: const Icon(Icons.person_add_outlined),
                        label: const Text('AJOUTER'),
                      ),
                    ),
                  ],
                ),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: FutureBuilder(
                  future: fetchEventParticipent(context),
                  builder: (context, snapshot) {
                    if (snapshot.hasError) {
                      return Text(snapshot.error.toString());
                    } else if (snapshot.hasData) {
                      return ListView.builder(
                        shrinkWrap: true,
                        itemCount: snapshot.data!.length,
                        itemBuilder: (BuildContext context, int index) {
                          return Card(
                            child: ListTile(
                              title: Text(
                                  '${snapshot.data![index].firstname} ${snapshot.data![index].lastname}'),
                              subtitle: Text(snapshot.data![index].email),
                            ),
                          );
                        },
                      );
                    } else {
                      return const CircularProgressIndicator();
                    }
                  },
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
