import 'dart:ffi';


class Location {
  final String idLocation;
  final String name;
  final Double lat;
  final Double long;
  final bool isRelated;
  final String isEvent;

  Location({
    required this.idLocation,
    required this.name,
    required this.lat,
    required this.long,
    required this.isRelated,
    required this.isEvent,
  });

  factory Location.fromMap(Map map) {
    Location location = Location(
      idLocation: map['id'],
      name: map['name'],
      lat: map['lat'],
      long: map['long'],
      
      isRelated: map['isRelated'] == 1 ? true : false,
      isEvent: map['isEvent']
    );

    return location;
  }
}
